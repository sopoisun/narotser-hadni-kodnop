<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Setting;
use Validator;

class SettingController extends Controller
{
    public function index()
    {
        $data = ['setting' => Setting::first()];
        return view(config('app.template').'.setting.setting', $data);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_faktur'  => 'required',
            'telp_faktur'   => 'required',
            'alamat_faktur' => 'required',
            'init_kode'     => 'required',
        ], [
            'title_faktur.required'     => 'Title faktur tidak boleh kosong.',
            'telp_faktur.required'      => 'Telp faktur tidak boleh kosong.',
            'alamat_faktur.required'    => 'Alamat faktur tidak boleh kosong.',
            'init_kode.required'        => 'Inisial Kode tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if( Setting::first()->update($request->all()) ){
            return redirect()->back()->with('succcess', 'Sukses ubah data setting.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data setting.']);
    }
}
