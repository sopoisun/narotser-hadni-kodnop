<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceRequest;
use App\Place;
use App\PlaceKategori;
use DB;
use Gate;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('place.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'places' => Place::with(['kategori', 'orderPlace' => function( $query ){
                            $query->join('orders', 'order_places.order_id', '=', 'orders.id')
                                ->where('orders.state', '=', 'Closed');
                        }])->where('active', 1)->get(),
        ];

        return view(config('app.template').'.place.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('place.create') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'types' => PlaceKategori::where('active', 1)->lists('nama', 'id'),
        ];

        return view(config('app.template').'.place.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceRequest $request)
    {
        if( Place::create($request->all()) ){
            return redirect('/place')->with('succcess', 'Sukses simpan data tempat pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data tempat pelanggan.']);
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
        if( Gate::denies('place.update') ){
            return view(config('app.template').'.error.403');
        }

        $place = Place::find($id);

        if( !$place ){
            return view(config('app.template').'.error.404');
        }

        $data = [
            'types' => PlaceKategori::where('active', 1)->lists('nama', 'id'),
            'place' => $place,
        ];

        return view(config('app.template').'.place.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceRequest $request, $id)
    {
        if( Place::find($id)->update($request->all()) ){
            return redirect('/place')->with('succcess', 'Sukses ubah data tempat pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data tempat pelanggan.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('place.delete') ){
            return view(config('app.template').'.error.403');
        }

        $place = Place::find($id);

        if( $place && $place->update(['active' => 0]) ){
            return redirect()->back()->with('succcess', 'Sukses hapus data '.$place->nama.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data tempat pelanggan.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Place::with(['kategori'])->where('active', 1)->where('id', $request->get('id'))->first();
        }elseif($request->get('ids')){
            return Place::with('kategori')->where('active', 1)->whereIn('id', explode('+', $request->get('ids')))->get();
        }else{
            return Place::with('kategori')->where('nama', 'like', '%'.$request->get('q').'%')->where('active', 1)
                        ->get();
        }
    }
}
