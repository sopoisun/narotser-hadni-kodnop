<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Karyawan;
use Validator;
use Hash;
use DB;
use Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$this->authorize('user.create');
        if( Gate::denies('user.create') ){
            return view(config('app.template').'.error.403');
        }

        $users  = User::join('karyawans', 'users.id', '=', 'karyawans.user_id')
                        ->with(['roles'])
                        ->select(['users.*', 'karyawans.nama'])->get();
        $data   = ['users' => $users];
        return view(config('app.template').'.user.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::where('name', '!=', 'superuser')->get();
        $data   = ['roles' => $roles];
        return view(config('app.template').'.user.create', $data);
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
            'karyawan_id'   => 'required',
            'username'      => 'required|unique:users,username',
            'password'      => 'required|confirmed',
            'roles'         => 'required',
        ], [
            'karyawan_id.required'  => "Karyawan tidak boleh kosong.",
            'username.required'     => "Username tidak boleh kosong.",
            'username.unique'       => "Username sudah dipakai.",
            'password.required'     => "Password tidak boleh kosong.",
            'password.confirmed'    => "Password konfirmasi tidak sama.",
            'roles.required'        => "Role tidak boleh kosong.",
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
        ]);

        if( $user ){
            $roles = $request->get('roles') != "" ? $request->get('roles') : [];
            $user->assignRole($roles);

            Karyawan::find($request->get('karyawan_id'))->update(['user_id' => $user->id]);

            return redirect('/user')->with('succcess', 'Sukses simpan user.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan user.']);
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
        $user = User::with(['karyawan', 'roles'])->find($id);

        if( !$user ){
            abort(404);
        }

        $roles  = Role::where('name', '!=', 'superuser')->get();
        $data   = ['roles' => $roles, 'user' => $user];
        return view(config('app.template').'.user.update', $data);
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
        $user = User::with(['roles'])->find($id);

        $inRoles   = $request->get('roles') != "" ? $request->get('roles') : [];
        $userRoles = array_column($user->roles->toArray(), 'id');

        // for new permissions
        $newRoles = array_diff($inRoles, $userRoles);
        if( count($newRoles) ){
            $user->assignRole($newRoles);
        }
        // for delete permissions
        $deleteRoles = array_diff($userRoles, $inRoles);
        if( count($deleteRoles) ){
            $user->revokeRole($deleteRoles);
        }

        return redirect()->back()->with('succcess', 'Sukses ubah user.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if( !$user ){
            abort(404);
        }

        $karyawan_id = $user->karyawan->id;

        if( $user->delete() ){

            DB::statement("UPDATE karyawans SET `user_id` = NULL where `id` = '$karyawan_id'");

            return redirect()->back()->with('succcess', 'Sukses hapus user.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus user.']);
    }

    public function changePassword()
    {
        $user = auth()->user();
        $data = ['user' => $user];
        return view(config('app.template').'.user.change-password', $data);
    }

    public function saveChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password'  => 'required',
            'password'      => 'required|confirmed',
        ], [
            'old_password.required' => "Old password tidak boleh kosong.",
            'password.required'     => "New Password tidak boleh kosong.",
            'password.confirmed'    => "Password konfirmasi tidak sama.",
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth()->user();

        if( !Hash::check($request->get('old_password'), $user->password) ){
            return redirect()->back()
                ->withErrors(['old_password' => 'Password lama sesuai.']);
        }

        if( $user->update(['password' => Hash::make($request->get('password'))]) ){
            return redirect()->back()->with('succcess', 'Sukses ubah password.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah password.']);
    }
}
