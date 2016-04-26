<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;
use App\Produk;
use App\ProdukKategori;
use App\ProdukDetail;
use Carbon\Carbon;
use DB;
use Gate;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('produk.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'produks' => Produk::allWithStokAndPrice()->get(),
        ];

        return view(config('app.template').".produk.table", $data);
    }

    public function stok()
    {
        if( Gate::denies('produk.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stok();

        return view(config('app.template').".produk.stok", $data);
    }

    public function stokPrint()
    {
        if( Gate::denies('produk.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stok();

        $print = new \App\Libraries\StokProduk([
            'header' => 'Laporan Stok Produk '.Carbon::now()->format('d M Y'),
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _stok()
    {
        $produks = Produk::stok()->orderBy('produks.id')->get();;
        return ['produks' => $produks];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('produk.create') ){
            return view(config('app.template').'.error.403');
        }

        $data = ['kategoris' => ProdukKategori::where('active', 1)->lists('nama', 'id')];
        return view(config('app.template').".produk.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdukRequest $request)
    {
        //return json_decode($request->get('produk_details'));
        $produkData = $request->only([
            'nama', 'satuan', 'satuan_beli', 'supplier_id', 'harga',
            'use_mark_up', 'mark_up', 'produk_kategori_id', 'qty_warning',
        ]);

        $produk_details = json_decode($request->get('produk_details'));

        $produkData['hpp']          = ( count($produk_details) == 0 ) ? $request->get('hpp') : 0;
        $produkData['harga']        = ( $request->get('use_mark_up') == 'Tidak' ) ? $request->get('harga') : 0;
        $produkData['konsinyasi']   = ( !$request->get('konsinyasi') ) ? 'Tidak' : 'Ya';

        $produk = Produk::create($produkData);
        $produkDetailData = [];
        foreach( $produk_details as $pd){
            array_push($produkDetailData, [
                'produk_id' => $produk->id,
                'bahan_id' => $pd->bahan_id,
                'qty' => $pd->qty,
            ]);
        }

        if( ProdukDetail::insert($produkDetailData) ){
            return redirect('/produk')->with('succcess', 'Sukses simpan data produk.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data produk.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Gate::denies('produk.update') ){
            return view(config('app.template').'.error.403');
        }

        $produk = Produk::with(['detail' => function($query){
            $query->join('bahans', 'produk_details.bahan_id', '=', 'bahans.id');
        }])->leftJoin('suppliers', 'produks.supplier_id', '=', 'suppliers.id')
        ->select(['produks.*', DB::raw('suppliers.nama_perusahaan as supplier')])
        ->find($id);

        if( !$produk ){
            return view(config('app.template').'.error.404');
        }

        $produk['konsinyasi'] = ($produk['konsinyasi'] == 'Ya') ? true : false;
        $data = ['produk' => $produk, 'kategoris' => ProdukKategori::where('active', 1)->lists('nama', 'id')];
        return view(config('app.template').'.produk.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdukRequest $request, $id)
    {
        $produk         = Produk::with('detail')->find($id);
        $produkDetail   = $produk->detail->toArray();

        $produkData     = $request->only([
            'nama', 'satuan', 'satuan_beli', 'supplier_id', 'harga',
            'use_mark_up', 'mark_up', 'produk_kategori_id', 'qty_warning',
        ]);

        $produkDetailData           = json_decode($request->get('produk_details'), true);

        $produkData['hpp']          = ( count($produkDetailData) == 0 ) ? $request->get('hpp') : 0;
        $produkData['harga']        = ( $request->get('use_mark_up') == 'Tidak' ) ? $request->get('harga') : 0;
        $produkData['konsinyasi']   = ( !$request->get('konsinyasi') ) ? 'Tidak' : 'Ya';

        if( $produk->update($produkData) ){

            if( count($produkDetailData) ){
                $produkDetailIds            = array_column($produkDetail, 'bahan_id');
                $produkDetailDataIds        = array_column($produkDetailData, 'bahan_id');

                // ambil data baru
                $new = array_diff($produkDetailDataIds, $produkDetailIds);
                $produkDetailDataNew = [];
                foreach($new as $key => $val){
                    array_push($produkDetailDataNew, [
                        'produk_id' => $id,
                        'bahan_id' => $val,
                        'qty' => $produkDetailData[$key]['qty'],
                    ]);
                }
                // do insert new data here
                ProdukDetail::insert($produkDetailDataNew);
                //return $produkDetailDataNew;

                // ambil data tetap
                $update = array_intersect($produkDetailDataIds, $produkDetailIds);
                $produkDetailDataUpdate = [];
                foreach($update as $key => $val){
                    $idxOri = array_search($val, $produkDetailIds);
                    $dOri = $produkDetail[$idxOri];
                    $idxInput = array_search($val, $produkDetailDataIds);
                    $dInput = $produkDetailData[$idxInput];

                    if($dOri['qty'] != $dInput['qty']){
                        array_push($produkDetailDataUpdate, [
                            'bahan_id' => $dOri['bahan_id'],
                            'qty' => $dInput['qty'],
                        ]);
                        // do update qty here
                        ProdukDetail::where('produk_id', $id)
                            ->where('bahan_id', $dOri['bahan_id'])
                            ->update(['qty' =>  $dInput['qty']]);
                    }
                }
                //return $produkDetailDataUpdate;

                // ambil data yang dihapus
                $delete = array_diff($produkDetailIds, $produkDetailDataIds);
                $produkDetailDataDelete = [];
                foreach($delete as $key => $val){
                    array_push($produkDetailDataDelete, [
                        'bahan_id' => $val,
                    ]);
                    // do delete here
                    ProdukDetail::where('produk_id', $id)
                        ->where('bahan_id', $val)
                        ->delete();
                }
                //return $produkDetailDataDelete;
            }

            return redirect('/produk')->with('succcess', 'Sukses ubah data produk.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data produk.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('produk.delete') ){
            return view(config('app.template').'.error.403');
        }

        $produk = Produk::find($id);

        if( $produk && $produk->update(['active' => 0]) ){
            ProdukDetail::where('produk_id', $id)->delete();
            return redirect()->back()->with('succcess', 'Sukses hapus data produk "'.$produk->nama.'".');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data produk.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            $produk = Produk::with(['detail' => function($query){
                $query->join('bahans', 'produk_details.bahan_id', '=', 'bahans.id');
            }])->where('active', 1)->where('id', $request->get('id'))->first();

            return [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'satuan' => $produk->satuan,
                'harga' => CountPrice($produk),
            ];
        }else{
            $produk = Produk::leftJoin('produk_details', 'produks.id', '=', 'produk_details.produk_id')
                ->where('nama', 'like', '%'.$request->get('q').'%')
                ->where('produks.active', 1)
                ->whereNotIn('produks.id', explode('+', $request->get('except')))
                ->select('produks.*')
                ->groupBy('produks.id')
                ->limit($request->get('page'));

            $produk = ( $request->get('without_has_bahan') == 'Ya' ) ?
                            $produk->whereNull('produk_details.id') : $produk;

            $produk = $produk->get();

            return $produk;
        }
    }
}
