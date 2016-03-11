<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PlaceKategori;
use Validator;
use Gate;

class PlaceKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('place_kategori.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = ['kategoris' => PlaceKategori::where('active', 1)->get()];
        return view(config('app.template').'.place-kategori.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('place_kategori.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.place-kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama kategori tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()
                ->back()->withErrors($validator)
                    ->withInput();
        }

        if( PlaceKategori::create($request->all()) ){
            return redirect('/place/kategori')->with('succcess', 'Sukses simpan data kategori tempat pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data kategori tempat pelanggan.']);
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
        if( Gate::denies('place_kategori.update') ){
            return view(config('app.template').'.error.403');
        }

        $kategori = PlaceKategori::find($id);

        if( !$kategori ){
            return view(config('app.template').'.error.404');
        }

        $data = ['kategori' => $kategori];
        return view(config('app.template').'.place-kategori.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama kategori tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()
                ->back()->withErrors($validator)
                    ->withInput();
        }

        if( PlaceKategori::find($id)->update($request->all()) ){
            return redirect('/place/kategori')->with('succcess', 'Sukses ubah data kategori tempat pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data kategori tempat pelanggan.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('place_kategori.delete') ){
            return view(config('app.template').'.error.403');
        }

        $kategori = PlaceKategori::find($id);

        if( $kategori && $kategori->update(['active' => 0]) ){
            return redirect()->back()->with('succcess', 'Sukses hapus data '.$kategori->nama.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data kategori tempat pelanggan.']);
    }
}
