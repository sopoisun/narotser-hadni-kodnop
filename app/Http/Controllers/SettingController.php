<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Setting;
use Validator;
use Gate;
use DB;

class SettingController extends Controller
{
    public function index()
    {
        if( Gate::denies('setting.update') ){
            return view(config('app.template').'.error.403');
        }

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
            'laba_procentage_warning' => 'required|numeric',
            'service_cost'  => 'required|numeric',
        ], [
            'title_faktur.required'     => 'Title faktur tidak boleh kosong.',
            'telp_faktur.required'      => 'Telp faktur tidak boleh kosong.',
            'alamat_faktur.required'    => 'Alamat faktur tidak boleh kosong.',
            'init_kode.required'        => 'Inisial Kode tidak boleh kosong.',
            'laba_procentage_warning.required'  => 'Input tidak boleh kosong.',
            'laba_procentage_warning.numeric'   => 'Input harus angka.',
            'service_cost.required'     => 'Biaya service tidak boleh kosong.',
            'service_cost.numeric'      => 'Input harus angka.',
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

    public function appReset()
    {
        return view(config('app.template').'.setting.reset');
    }

    public function saveAppReset(Request $request)
    {
        $tables = $request->get('tables') ? $request->get('tables') : [];

        // Transaksi
        DB::table('orders')->truncate();
        DB::table('order_taxes')->truncate();
        DB::table('order_places')->truncate();
        DB::table('order_merges')->truncate();
        DB::table('order_details')->truncate();
        DB::table('order_detail_returns')->truncate();
        DB::table('order_detail_bahans')->truncate();
        DB::table('order_cancels')->truncate();
        DB::table('order_bayars')->truncate();
        DB::table('order_bayar_banks')->truncate();
        // Pembelian
        DB::table('pembelians')->truncate();
        DB::table('pembelian_details')->truncate();
        DB::table('pembelian_bayars')->truncate();
        // Adjustment
        DB::table('adjustments')->truncate();
        DB::table('adjustment_details')->truncate();
        // Average Price Notes
        DB::table('average_price_actions')->truncate();

        if( in_array("accounts", $tables) ){
            DB::table('accounts')->whereNotIn('id', [1, 2, 3, 4, 5])->delete();
            DB::table('account_report')->whereNotIn('account_id', [1, 2, 3, 4, 5])->delete();
            DB::table('account_saldos')->truncate();
        }

        if( in_array("bahans", $tables) ){
            DB::table('bahans')->truncate();
        }

        if( in_array("banks", $tables) ){
            DB::table('banks')->truncate();
        }

        if( in_array("customers", $tables) ){
            DB::table('customers')->truncate();
        }

        if( in_array("karyawans", $tables) ){
            DB::table('karyawans')->whereNotIn('id', [1])->delete();
        }

        if( in_array("place_kategoris", $tables) ){
            DB::table('place_kategoris')->truncate();
        }

        if( in_array("places", $tables) ){
            DB::table('places')->truncate();
        }

        if( in_array("produk_kategoris", $tables) ){
            DB::table('produk_kategoris')->truncate();
        }

        if( in_array("produks", $tables) ){
            DB::table('produks')->truncate();
            DB::table('produk_details')->truncate();
        }

        if( in_array("settings", $tables) ){
            Setting::first()->update([
                'title_faktur' => '',
                'alamat_faktur' => '',
                'telp_faktur' => '',
                'init_kode' => '',
                'laba_procentage_warning' => 0,
                'service_cost' => 0,
            ]);
        }

        if( in_array("suppliers", $tables) ){
            DB::table('suppliers')->truncate();
        }

        if( in_array("taxes", $tables) ){
            DB::table('taxes')->truncate();
        }

        if( in_array("users", $tables) ){
            DB::table('users')->whereNotIn('id', [3])->delete();
        }

        return redirect()->back()->with('succcess', 'Sukses reset aplikasi.');
    }
}
