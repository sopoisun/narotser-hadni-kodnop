<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Permission;
use Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit  = 20;
        $data   = [
            'limit'         => $limit,
            'permissions'   => Permission::paginate($limit)->setPath('permission'),
        ];
        return view(config('app.template').'.permission.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(config('app.template').'.permission.create');
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
            'display'   => 'required',
            'name'      => 'required',
        ], [
            'display.required'  => 'Opsi tidak boleh kosong.',
            'name.required'     => 'Key tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if( Permission::create($request->all()) ){
            return redirect('/user/permission')->with('succcess', 'Sukses simpan permission.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan permission.']);
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
        $permission = Permission::find($id);

        if( !$permission ){
            abort(404);
        }

        $data = ['permission' => $permission];
        return view(config('app.template').'.permission.update', $data);
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
            'display'   => 'required',
            'name'      => 'required',
        ], [
            'display.required'  => 'Opsi tidak boleh kosong.',
            'name.required'     => 'Key tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if( Permission::find($id)->update($request->all()) ){
            return redirect('/user/permission')->with('succcess', 'Sukses ubah permission.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah permission.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if( $permission && $permission->delete() ){
            return redirect()->back()->with('succcess', 'Sukses hapus permission.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus permission.']);
    }
}
