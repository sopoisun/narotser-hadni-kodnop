<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaxRequest;
use App\Tax;
use Gate;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('tax.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'taxes' => Tax::where('active', 1)->get(),
        ];

        return view(config('app.template').'.tax.table', $data);
    }

    public function create()
    {
        if( Gate::denies('tax.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.tax.create');
    }

    public function store(TaxRequest $request)
    {
        if( Tax::create($request->all()) ){
            return redirect('/tax')->with('succcess', 'Sukses simpan data pajak pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data pajak pelanggan.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Gate::denies('tax.update') ){
            return view(config('app.template').'.error.403');
        }

        $tax = Tax::find($id);

        if( !$tax ){
            return view(config('app.template').'.error.404');
        }

        $data = [ 'tax' => $tax ];
        return view(config('app.template').'.tax.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaxRequest $request, $id)
    {
        if( Tax::find($id)->update($request->all()) ){
            return redirect('/tax')->with('succcess', 'Sukses ubah data pajak pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data pajak pelanggan.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Tax::where('active', 1)->where('id', $request->get('id'))->first();
        }

        return Tax::where('active', 1)->get();
    }
}
