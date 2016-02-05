<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\BahanRequest;
use App\Bahan;
use DB;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'bahans' => Bahan::all(),
        ];

        return view(config('app.template').'.bahan.table', $data);
    }

    public function stok()
    {
        $bahans = Bahan::stok()->orderBy('bahans.id')->get();
        $data = ['bahans' => $bahans];
        return view(config('app.template').'.bahan.stok', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $bahan = Bahan::find($id);

        if( !$bahan ){
            abort(404);
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
        $bahan = Bahan::find($id);

        if( $bahan && $bahan->delete() ){
            return redirect()->back()->with('succcess', 'Sukses hapus data bahan produksi "'.$bahan->nama.'".');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data bahan produksi.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Bahan::find($request->get('id'));
        }else{
            return Bahan::where('nama', 'like', '%'.$request->get('q').'%')
                ->whereNotIn('id', explode('+', $request->get('except')))
                ->limit($request->get('page'))->get();
        }
    }
}
