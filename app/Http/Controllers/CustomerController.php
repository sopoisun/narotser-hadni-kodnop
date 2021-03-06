<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CustomerRequest;
use App\Http\Controllers\Controller;
use App\Customer;
use Validator;
use Uuid;
use DB;
use Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( Gate::denies('customer.read') ){
            return view(config('app.template').'.error.403');
        }

        $type = $request->get('type') ? $request->get('type') : 'registered';

        if( $type == 'registered' ){
            $customers  = Customer::join('customer_purchases', 'customers.id', '=', 'customer_purchases.customer_id')
                            ->select([
                                'customers.*',
                                DB::raw('customer_purchases.visit as jumlah_kunjungan'),
                                DB::raw('customer_purchases.purchase as total'),
                            ])
                            ->paginate(20);
            $data       = ['customers' => $customers];
            return view(config('app.template').'.customer.table', $data);
        }elseif( $type == 'unregistered' ){
            $customers  = Customer::whereNull('nama')->get();
            $data       = ['customers' => $customers];
            return view(config('app.template').'.customer.table-empty', $data);
        }else{
            abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('customer.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.customer.create');
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
            'jumlah' => 'required|numeric',
        ], [
            'jumlah.required' => 'Jumlah tidak boleh kosong.',
            'jumlah.numeric' => 'Input harus angka.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lastId     = 1;
        $lastIdDB   = Customer::lastCustomerID();

        if( $lastIdDB ){
            $lastId = $lastIdDB->kode + 1;
        }

        $insertData = [];
        for( $i = 0; $i<$request->get('jumlah'); $i++ ){
            $code = ($i + $lastId);
            array_push($insertData, ['kode' => str_pad($code, 10, '0', STR_PAD_LEFT)]);
        }

        if( Customer::insert($insertData) ){
            return redirect('/customer?type=unregistered')->with('succcess', 'Sukses buat data id pelanggan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal buat data id pelanggan.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Gate::denies('customer.update') ){
            return view(config('app.template').'.error.403');
        }

        $customer = Customer::find($id);

        if( !$customer ){
            return view(config('app.template').'.error.404');
        }

        $data = ['customer' => $customer];
        return view(config('app.template').'.customer.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        if( Customer::find($id)->update($request->all()) ){
            return redirect('/customer')->with('succcess', 'Sukses ubah customer.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah customer.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Customer::find($request->get('id'));
        }elseif($request->get('ids')){
            return Customer::whereIn('id', explode('+', $request->get('ids')))->get();
        }else{
            return Customer::where('kode', 'like', '%'.$request->get('q').'%')
                        ->get();
        }
    }
}
