<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Artisan;
use Validator;
use Gate;

class AutoDataController extends Controller
{
    public function index(Request $request)
    {
        if( Gate::denies('autodata.date') ){
            return view(config('app.template').'.error.403');
        }

        if( $request->isMethod('POST') ){
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required|date',
            ], [
                'tanggal.required'  => 'Tanggal tidak boleh kosong',
                'tanggal.date'      => 'Input harus tanggal (Y-m-d)',
            ]);

            if( $validator->fails() ){
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $tanggal    = $request->get('tanggal');
            $type       = $request->get('type');

            Artisan::call($type, [ 'tanggal' => $tanggal ]);

            return redirect()->back()->with('message', 'Perintah sudah dieksekusi. Mungkin butuh beberapa menit untuk
                menyelesaikannya. Mohon ditunggu, kemudian cek di jurnal akun. :)');
        }

        return view(config('app.template').'.auto-data.date', ['tanggal' => Carbon::now()]);
    }

    public function range(Request $request)
    {
        if( Gate::denies('autodata.date-range') ){
            return view(config('app.template').'.error.403');
        }

        if( $request->isMethod('POST') ){
            $validator = Validator::make($request->all(), [
                'tanggal'       => 'required|date',
                'to_tanggal'    => 'required|date',
            ], [
                'tanggal.required'  => 'Tanggal tidak boleh kosong',
                'tanggal.date'      => 'Input harus tanggal (Y-m-d)',
                'to_tanggal.required'  => 'Tanggal2 tidak boleh kosong',
                'to_tanggal.date'      => 'Input harus tanggal (Y-m-d)',
            ]);

            if( $validator->fails() ){
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $tanggal    = $request->get('tanggal');
            $to_tanggal = $request->get('to_tanggal');
            $type       = $request->get('type');

            Artisan::call($type, [ 'tanggal' => $tanggal, 'to_tanggal' => $to_tanggal ]);

            return redirect()->back()->with('message', 'Perintah sudah dieksekusi. Mungkin butuh beberapa menit untuk
                menyelesaikannya. Mohon ditunggu, kemudian cek di jurnal akun. :)');
        }

        $tanggal = Carbon::now();

        return view(config('app.template').'.auto-data.date-range', [
            'tanggal'       => $tanggal,
            'to_tanggal'    => $tanggal->copy(),
        ]);
    }

    public function stok(Request $request)
    {
        if( Gate::denies('autodata.stok') ){
            return view(config('app.template').'.error.403');
        }

        if( $request->isMethod('POST') ){
            $type = $request->get('type');

            Artisan::call($type);

            return redirect()->back()->with('message', "Perintah sudah dieksekusi. Mungkin butuh beberapa menit untuk
                menyelesaikannya.");
        }

        return view(config('app.template').'.auto-data.stok');
    }
}
