<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\BahanRequest;
use App\Bahan;
use Carbon\Carbon;
use DB;
use Gate;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('bahan.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'bahans' => Bahan::where('active', 1)->get(),
        ];

        return view(config('app.template').'.bahan.table', $data);
    }

    public function stok()
    {
        if( Gate::denies('bahan.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stok();

        return view(config('app.template').'.bahan.stok', $data);
    }

    public function stokPrint()
    {
        if( Gate::denies('bahan.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stok();

        $print = new \App\Libraries\StokBahan([
            'header' => 'Laporan Stok Bahan '.Carbon::now()->format('d M Y'),
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _stok()
    {
        $bahans = Bahan::stok()->orderBy('bahans.id')->get();
        return ['bahans' => $bahans];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('bahan.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.bahan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BahanRequest $request)
    {
        if( Bahan::create($request->all()) ){
            return redirect('/bahan-produksi')->with('succcess', 'Sukses simpan data bahan produksi.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data bahan produksi.']);
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
        if( Gate::denies('bahan.update') ){
            return view(config('app.template').'.error.403');
        }

        $bahan = Bahan::find($id);

        if( !$bahan ){
            return view(config('app.template').'.error.404');
        }

        $data = ['bahan' => $bahan];

        return view(config('app.template').'.bahan.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BahanRequest $request, $id)
    {
        if( Bahan::find($id)->update($request->all()) ){
            return redirect('/bahan-produksi')->with('succcess', 'Sukses ubah data bahan produksi.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data bahan produksi.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('bahan.delete') ){
            return view(config('app.template').'.error.403');
        }

        $bahan = Bahan::find($id);

        if( $bahan && $bahan->update(['active' => 0]) ){
            return redirect()->back()->with('succcess', 'Sukses hapus data bahan produksi "'.$bahan->nama.'".');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data bahan produksi.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Bahan::where('active', 1)->where('id', $request->get('id'))->first();
        }else{
            return Bahan::where('nama', 'like', '%'.$request->get('q').'%')
                ->where('active', 1)
                ->whereNotIn('id', explode('+', $request->get('except')))
                ->limit($request->get('page'))->get();
        }
    }
}
