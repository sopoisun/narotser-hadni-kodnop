<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Supplier;
use Input;
use Gate;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('supplier.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'suppliers' => Supplier::where('active', 1)->get(),
        ];

        return view(config('app.template').'.supplier.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('supplier.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        if( Supplier::create($request->all()) ){
            return redirect('/supplier')->with('succcess', 'Sukses simpan data supplier.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data supplier.']);
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
        if( Gate::denies('supplier.update') ){
            return view(config('app.template').'.error.403');
        }

        $supplier = Supplier::find($id);

        if( !$supplier ){
            return view(config('app.template').'.error.404');
        }

        $data = [
            'supplier' => $supplier,
        ];

        return view(config('app.template').'.supplier.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        if( Supplier::find($id)->update($request->all()) ){
            return redirect('/supplier')->with('succcess', 'Sukses ubah data supplier.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data supplier.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('supplier.delete') ){
            return view(config('app.template').'.error.403');
        }

        $supplier = Supplier::find($id);

        if( $supplier && $supplier->update(['active' => 0]) ){
            return redirect()->back()->with('succcess', 'Sukses hapus data Supplier '.$supplier->nama.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data Supplier.']);
    }

    public function ajaxLoad(Request $request)
    {
        return Supplier::where('nama_perusahaan', 'like', '%'.$request->get('q').'%')
            ->where('active', 1)
            ->limit($request->get('page_limit'))
            ->get();
    }
}
