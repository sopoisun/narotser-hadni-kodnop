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
            $customers  = Customer::leftJoin(DB::raw("
                            (SELECT orders.`id`, orders.`customer_id`, SUM(order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)))
                            AS total_penjualan FROM orders INNER JOIN order_details ON orders.`id` = order_details.`order_id`
                            LEFT JOIN order_detail_returns on order_details.id = order_detail_returns.order_detail_id
                            WHERE orders.`state` = 'Closed' GROUP BY orders.`id`)AS temp_orders
                        "), 'customers.id', '=', 'temp_orders.customer_id')
                        ->whereNotNull('customers.nama')
                        ->select([
                            'customers.*', DB::raw('count(temp_orders.id) as jumlah_kunjungan'),
                            DB::raw('sum(temp_orders.total_penjualan)as total')
                        ])
                        ->groupBy('customers.id')
                        ->paginate(2);
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

        $digit = 15;

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $customerCodes = [];
        for( $i = 0; $i<$request->get('jumlah'); $i++ ){
            array_push($customerCodes, strtoupper(str_random($digit)));
        }

        $c = Customer::whereIn('kode', $customerCodes);
        $exist = $c->count();

        while( $exist ){
            foreach ($c->get() as $val) {
                $idx = array_search($val->kode, $customerCodes);
                unset($customerCodes[$idx]);
                array_push($customerCodes, strtoupper(str_random($digit)));
            }

            $c = Customer::whereIn('kode', $customerCodes);
            $exist = $c->count();
        }

        $insertData = [];
        foreach( $customerCodes as $cc ){
            array_push($insertData, ['kode' => $cc]);
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
