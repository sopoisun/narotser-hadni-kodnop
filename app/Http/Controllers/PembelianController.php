<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PembelianRequest;
use App\Http\Requests\PembelianBayarRequest;
use App\Pembelian;
use App\PembelianDetail;
use App\PembelianBayar;
use Auth;
use DB;
use Gate;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('pembelian.read') ){
            return view(config('app.template').'.error.403');
        }

         $data = [
             'pembelians' => Pembelian::with('detail', 'bayar', 'supplier', 'karyawan')
                ->orderBy('tanggal', 'desc')->paginate(20),
         ];

         return view(config('app.template').".pembelian.table", $data);
    }

    public function detail($id)
    {
        if( Gate::denies('pembelian.read.detail') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'id' => $id,
            'details' => PembelianDetail::with('bahan', 'produk')
                            ->where('pembelian_id', $id)->get(),
        ];

        return view(config('app.template').'.pembelian.table-detail', $data);
    }

    public function create(Request $request)
    {
        if( Gate::denies('pembelian.create') ){
            return view(config('app.template').'.error.403');
        }

        if( !$request->old() ){
            $request->session()->forget('data_pembelian');
        }

        return view(config('app.template').'.pembelian.create');
    }

    public function store(PembelianRequest $request)
    {
        $denied = false;

        if( !$request->session()->has('data_pembelian') ){
            $denied = true;
        }else{
            $beliBahan = $request->session()->has('data_pembelian.bahan') ? $request->session()->get('data_pembelian.bahan') : [];
            $beliProduk = $request->session()->has('data_pembelian.produk') ? $request->session()->get('data_pembelian.produk') : [];

            if( empty($beliBahan) && empty($beliProduk) ){
                $denied = true;
            }
        }

        if( $denied ){
            return redirect()->back()
                ->withInput()->withErrors(['no_details' => 'Tidak ada barang yang dibeli.']);
        }

        // Pembelian
        $karyawan_id    = Auth::check() ? Auth::user()->karyawan->id : '1';
        $input          = $request->only(['supplier_id', 'tanggal']) + ['karyawan_id' => $karyawan_id];
        $pembelian      = Pembelian::create($input);
        // Pembelian Bayar
        if( $request->get('bayar') != "0" ){
            $input = [
                'pembelian_id'  => $pembelian->id,
                'nominal'       => $request->get('bayar'),
                'karyawan_id'   => $karyawan_id,
                'tanggal'       => $request->get('tanggal'),
            ];
            PembelianBayar::create($input);
        }

        # Update [Bahan => Harga, Produk => HPP]
        // Bahan
        if( !empty($beliBahan) ){
            $keys   = array_keys($beliBahan);
            $bahans = \App\Bahan::stok()
                ->whereIn('bahans.id', $keys)
                ->orderBy('bahans.id')
                ->get();

            foreach($bahans as $bahan){
                $bId        = $bahan->id;
                $inHarga    = $beliBahan[$bId]['harga'] / $beliBahan[$bId]['stok'];

                if( $bahan->harga != $inHarga ){
                    /*$sum = [];
                    for($i=0; $i<$bahan->sisa_stok; $i++){
                        array_push($sum, $bahan->harga);
                    }
                    for($i=0; $i<$beliBahan[$bId]['stok']; $i++){
                        array_push($sum, $inHarga);
                    }
                    $harga = Pembulatan(collect($sum)->avg());*/

                    $oldPrice   = $bahan->harga;
                    $oldStok    = $bahan->sisa_stok;
                    $inputPrice = $inHarga;
                    $inputStok  = $beliBahan[$bId]['stok'];
                    //$harga      = Pembulatan((($oldPrice*$oldStok)+($inputPrice*$inputStok))/($oldStok+$inputStok));
                    $harga      = ((($oldPrice*$oldStok)+($inputPrice*$inputStok))/($oldStok+$inputStok));

                    if( $harga != $bahan->harga ){
                        //echo "<pre>", print_r(['id' => $bId, 'nama' => $bahan->nama, 'harga' => $harga]), "</pre>";
                        \App\Bahan::find($bId)->update(['harga' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'bahan',
                            'relation_id'       => $bId,
                            'old_price'         => $bahan->harga,
                            'old_stok'          => $oldStok,
                            'input_price'       => $inHarga,
                            'input_stok'        => $inputStok,
                            'average_with_round'=> $harga,
                            'action'            => "Pembelian #".$pembelian->id,
                        ]);
                    }
                }
            }
        }
        // Produk, Harga => HPP
        if( !empty($beliProduk) ){
            $keys    = array_keys($beliProduk);
            $produks = \App\Produk::stok()
                ->whereIn('produks.id', $keys)
                ->orderBy('produks.id')
                ->get();
            foreach($produks as $produk){
                $pId        = $produk->id;
                $inHarga    = $beliProduk[$pId]['harga'] / $beliProduk[$pId]['stok'];

                if( $produk->hpp != $inHarga ){
                    /*$sum = [];
                    for($i=0; $i<$produk->sisa_stok; $i++){
                        array_push($sum, $produk->hpp);
                    }
                    for($i=0; $i<$beliProduk[$pId]['stok']; $i++){
                        array_push($sum, $inHarga);
                    }
                    $harga = Pembulatan(collect($sum)->avg()); // HPP*/

                    $oldPrice   = $produk->hpp;
                    $oldStok    = $produk->sisa_stok;
                    $inputPrice = $inHarga;
                    $inputStok  = $beliProduk[$pId]['stok'];
                    //$harga      = Pembulatan((($oldPrice*$oldStok)+($inputPrice*$inputStok))/($oldStok+$inputStok));
                    $harga      = ((($oldPrice*$oldStok)+($inputPrice*$inputStok))/($oldStok+$inputStok));

                    if( $harga != $produk->hpp  ){
                        //echo "<pre>", print_r(['id' => $pId, 'nama' => $produk->nama, 'harga' => $harga]), "</pre>";
                        \App\Produk::find($pId)->update(['hpp' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'produk',
                            'relation_id'       => $pId,
                            'old_price'         => $produk->hpp,
                            'old_stok'          => $oldStok,
                            'input_price'       => $inHarga,
                            'input_stok'        => $inputStok,
                            'average_with_round'=> $harga,
                            'action'            => "Pembelian #".$pembelian->id,
                        ]);
                    }
                }
            }
        }

        // Pembelian Detail
        $details  = array_merge($beliBahan, $beliProduk);
        $temp = [];
        foreach( $details as $detail ){
            array_push($temp, ($detail + ['pembelian_id' => $pembelian->id]));
        }
        PembelianDetail::insert($temp);

        $request->session()->forget('data_pembelian');

        return redirect('/pembelian')->with('succcess', 'Sukses simpan data pembelian.');
    }

    public function bayar($id)
    {
        if( Gate::denies('pembelian.bayar') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'id'     => $id,
            'total'  => PembelianDetail::where('pembelian_id', $id)->get()->sum('harga'),
            'bayars' => PembelianBayar::with('karyawan')
                        ->where('pembelian_id', $id)
                        ->orderBy('tanggal')->get(),
        ];

        return view(config('app.template').'.pembelian.table-bayar', $data);
    }

    public function bayarStore(PembelianBayarRequest $request, $id)
    {
        $input = $request->only(['tanggal', 'nominal']) + [
            'pembelian_id' => $id,
            'karyawan_id' => '1',
        ];

        if( PembelianBayar::create($input) ){
            return redirect()->back()->with('succcess', 'Sukses simpan pembayaran.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan pembayaran.']);
    }

    public function showItem(Request $request)
    {
        return $request->session()->get('data_pembelian');
    }

    public function saveItem(Request $request)
    {
        if( $request->get('relation_id') && $request->get('qty') && $request->get('satuan')
            && $request->get('harga') && $request->get('stok') && $request->get('type') ){

            $dataPembelian = $request->session()->has('data_pembelian.'.$request->get('type')) ?
                                $request->session()->get('data_pembelian.'.$request->get('type')) : [];

            if( !array_key_exists($request->get('relation_id'),  $dataPembelian) ){
                $id = $request->get('relation_id');
                $dataPembelian[$id] = $request->only(['type', 'relation_id', 'qty', 'satuan', 'harga', 'stok']);
                $request->session()->put('data_pembelian.'.$request->get('type'), $dataPembelian);
                $dataRet = $dataPembelian[$id];
                $dataRet['harga'] = number_format($request->get('harga'), 0, ',', '.');
                return $dataRet;
            }
        }

        return $request->all();
    }

    public function removeItem(Request $request)
    {
        if( $request->get('id') && $request->get('type') ){
            $request->session()->forget('data_pembelian.'.$request->get('type').'.'.$request->get('id'));
        }else{
            return view(config('app.template').'.error.404');
        }
    }
}
