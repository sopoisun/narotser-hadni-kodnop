<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['roles' => Role::all()];
        return view(config('app.template').'.role.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = ['permissions' => Permission::all()];
        return view(config('app.template').'.role.create', $data);
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
            'name'      => 'required',
        ], [
            'name.required'     => 'Nama Role tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create($request->all());

        if( $role ){
            $role->addPermission($request->get('permissions'));

            return redirect('/user/role')->with('succcess', 'Sukses simpan role.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan role.']);
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
        $role = Role::with('permissions')->find($id);

        if( !$role ){
            abort(404);
        }

        $data = [
            'role'          => $role,
            'permissions'   => Permission::all(),
        ];

        return view(config('app.template').'.role.update', $data);
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
            'name'      => 'required',
        ], [
            'name.required'     => 'Nama Role tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::with('permissions')->find($id);

        $inPermission   = $request->get('permissions');
        $rolePermission = array_column($role->permissions->toArray(), 'id');

        if( $role->update($request->all()) ){
            // for new permissions
            $newPermission = array_diff($inPermission, $rolePermission);
            $role->addPermission($newPermission);
            // for delete permissions
            $deletePermission = array_diff($rolePermission, $inPermission);
            $role->removePermission($deletePermission);

            return redirect('/user/role')->with('succcess', 'Sukses ubah role.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah role.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if( $role && $role->delete() ){
            return redirect()->back()->with('succcess', 'Sukses hapus role.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus role.']);
    }
}
