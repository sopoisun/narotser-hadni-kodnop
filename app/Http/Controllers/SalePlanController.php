<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SalePlan;
use App\SalePlanDetail;
use DB;
use Gate;
use Validator;

class SalePlanController extends Controller
{
    public function index()
    {
        if( Gate::denies('saleplan.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'sale_plans' => SalePlan::orderBy('tanggal', 'desc')->paginate(20),
        ];

        return view(config('app.template').'.saleplan.table', $data);
    }

    public function detail($id)
    {
        if( Gate::denies('saleplan.detail') ){
            return view(config('app.template').'.error.403');
        }

        $salePlan = SalePlan::find($id);

        if( !$salePlan ){
            return view(config('app.template').'.error.404');
        }

        $data = [
            'salePlan'  => $salePlan,
            'details'   => SalePlanDetail::with('produk')
                            ->where('sale_plan_id', $id)->get(),
        ];

        return view(config('app.template').'.saleplan.table-detail', $data);
    }

    public function create(Request $request)
    {
        if( !$request->old() ){
            $request->session()->forget('data_saleplan');
        }

        return view(config('app.template').'.saleplan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'   => 'required|date',
        ], [
            'tanggal.required'  => 'Tanggal tidak boleh kosong',
            'tanggal.date'      => 'Input harus tanggal',
        ]);

        if( $validator->fails() ){
            return redirect()->back()->withInput()
                ->withErrors($validator);
        }

        $dataSalePlan = $request->session()->has('data_saleplan') ? $request->session()->get('data_saleplan') : [];

        if( empty($dataSalePlan) ){
            return redirect()->back()->withInput()
                ->withErrors(['no_details' => 'Tidak ada barang yang dibeli.']);
        }

        $salePlan = SalePlan::create([
            'kode_plan' => "SP-".date('dmY-his'),
            'tanggal'   => $request->get('tanggal'),
        ]);

        foreach ($dataSalePlan as $d) {
            SalePlanDetail::create([
                'sale_plan_id'  => $salePlan->id,
                'produk_id'     => $d['id'], // produk_id
                'qty'           => $d['qty'],
                'harga'         => $d['harga'],
            ]);
        }

        $request->session()->forget('data_saleplan');

        return redirect('/pembelian/saleplan');
    }

    public function itemSession(Request $request)
    {
        return $request->session()->has('data_saleplan') ?
            $request->session()->get('data_saleplan') : [];
    }

    public function saveItem(Request $request)
    {
        if( $request->get('id') && $request->get('qty') && $request->get('harga') ){
            $dataSalePlan = $request->session()->has('data_saleplan') ?
                                $request->session()->get('data_saleplan') : [];

            if( !array_key_exists($request->get('id'),  $dataSalePlan) ){
                $id = $request->get('id');
                $dataSalePlan[$id] = $request->only(['id', 'nama', 'qty', 'harga']);
                $request->session()->put('data_saleplan', $dataSalePlan);
                $dataRet = $request->all();
                $dataRet['harga']       = number_format($request->get('harga'), 0, ',', '.');
                $dataRet['subtotal']    = number_format($request->get('harga')*$request->get('qty'), 0, ',', '.');
                return $dataRet;
            }
        }
    }

    public function removeItem(Request $request)
    {
        if( $request->get('id') ){
            $request->session()->forget('data_saleplan.'.$request->get('id'));
        }else{
            return view(config('app.template').'.error.404');
        }
    }
}
