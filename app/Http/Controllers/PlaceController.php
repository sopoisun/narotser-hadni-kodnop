<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceRequest;
use App\Place;
use App\PlaceKategori;
use DB;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'places' => Place::with(['kategori', 'orderPlace' => function( $query ){
                            $query->join('orders', 'order_places.order_id', '=', 'orders.id')
                                ->where('orders.state', '=', 'Closed');
                        }])->get(),
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
        $data = [
            'types' => PlaceKategori::lists('nama', 'id'),
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
        $place = Place::find($id);

        if( !$place ){
            abort(404);
        }

        $data = [
            'types' => PlaceKategori::lists('nama', 'id'),
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
        $place = Place::find($id);

        if( $place && $place->delete() ){
            return redirect()->back()->with('succcess', 'Sukses hapus data '.$place->nama.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data tempat pelanggan.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Place::find($request->get('id'));
        }elseif($request->get('ids')){
            return Place::whereIn('id', explode('+', $request->get('ids')))->get();
        }else{
            return Place::where('nama', 'like', '%'.$request->get('q').'%')
                        ->get();
        }
    }
}
