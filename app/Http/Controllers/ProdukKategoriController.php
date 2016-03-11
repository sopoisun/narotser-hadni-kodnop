<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProdukKategori;
use App\Http\Requests\ProdukKategoriRequest;
use Gate;

class ProdukKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('produk_kategori.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'kategoris' => ProdukKategori::where('active', 1)->get(),
        ];

        return view(config('app.template').'.produk-kategori.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('produk_kategori.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.produk-kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdukKategoriRequest $request)
    {
        if( ProdukKategori::create($request->all()) ){
            return redirect('/produk/kategori')->with('succcess', 'Sukses simpan data kategori produk.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data kategori produk.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Gate::denies('produk_kategori.update') ){
            return view(config('app.template').'.error.403');
        }

        $kategori = ProdukKategori::find($id);

        if( !$kategori ){
            return view(config('app.template').'.error.404');
        }

        $data = ['kategori' => $kategori];
        return view(config('app.template').'.produk-kategori.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdukKategoriRequest $request, $id)
    {
        if( ProdukKategori::find($id)->update($request->all()) ){
            return redirect('/produk/kategori')->with('succcess', 'Sukses ubah data kategori produk.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data kategori produk.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('produk_kategori.delete') ){
            return view(config('app.template').'.error.403');
        }

        $kategori = ProdukKategori::find($id);

        if( $kategori && $kategori->update(['active' => 0]) ){
            return redirect()->back()->with('succcess', 'Sukses hapus data '.$kategori->nama.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data kategori produk.']);
    }
}
