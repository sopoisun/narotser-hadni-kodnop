<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\Setting;
use App\User;
use App\Produk;
use App\Karyawan;
use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Artisan;
use JWTAuth;

class MobileController extends Controller
{
    public function index(Request $request) // as Login
    {
        $credentials = $request->only([
            'username', 'password'
        ]) + [
            'active' => 1,
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['result' => 'wrong email or password.']);
        }

        return response()->json(['result' => $token]);
    }

    public function me(Request $request)
    {
        $token = $request->get('token');
    	$me = JWTAuth::toUser($token);
        return response()->json(['result' => $me->load('karyawan')]);
    }

    public function produk()
    {
        $produks = Produk::allWithStokAndPrice()->get();

        $data = [];
        $no = 0;
        foreach($produks as $produk)
        {
            $no++;
            array_push($data, [
                'no' => $no,
                'produk_id' => $produk->id,
                'nama_produk' => $produk->nama,
                'harga' => Pembulatan($produk->harga_jual),
                'harga_f' => number_format(Pembulatan($produk->harga_jual), 0, ",", "."),
                'kategori' => $produk->nama_kategori,
            ]);
        }

        return response()->json(['result' => $data]);
    }

    public function place()
    {
        $places = \App\Place::where('active', 1)->with('kategori')->get();

        $data = [];
        foreach($places as $place)
        {
            array_push($data, [
                'place_id'    => $place->id,
                'nama'  => $place->nama,
                'kategori' => $place->kategori->nama,
                'harga' => $place->harga,
            ]);
        }

        return response()->json(['result' => $data]);
    }

    public function checkStok(Request $request)
    {
        \Debugbar::disable();

        //return 1; // allow transaction with >=0 stok

        $produkId   = $request->get('id');
        $qty        = $request->get('qty') ? $request->get('qty') : 1;
        $produk     = Produk::with('detail')->where('active', 1)->where('id', $produkId)->first();

        $denied = false;
        if( $produk->detail->count() ){
            $tempBahan = [];
            foreach( $produk->detail as $pd ){
                $bId = $pd['bahan_id'];
                $tempBahan[$bId] =( $pd['qty'] * $qty );
            }

            $bahans = \App\StokBahan::whereIn('bahan_id', array_keys($tempBahan))->get();
            foreach($bahans as $bahan){
                $bId = $bahan->bahan_id;
                if( $bahan->stok < $tempBahan[$bId] ){
                    $denied = true;
                }
            }
        }else{
            $produk = \App\StokProduk::where('produk_id', $produkId)->first();
            if( $produk->stok < $qty ){
                $denied = true;
            }
        }

        if( !$denied ){
            return 1;
        }

        return 0;
    }

    public function OpenTransaksi(Request $request)
    {
        $token = $request->get('token');
    	$me = JWTAuth::toUser($token);

        $response = [ 'code' => 0, 'message' => "Unknow error!" ];

        $data_order_detail = json_decode($request->get('data_order'), true);
        // Convert like data session
        $temp = [];
        foreach ($data_order_detail as $d) {
            $key = $d['id'];
            $temp[$key] = $d;
        }
        $data_order_detail = $temp;

        # Create Nota
        $setting = Setting::first();
        // Get Last Order
        $tanggal    = $request->get('tanggal');
        $lastOrder  = Order::where('tanggal', $tanggal)->get()->count();
        $nota       = $setting->init_kode."-".str_replace('-', '', date('dmY', strtotime($tanggal))).($lastOrder+1);

        // Order
        $karyawan_id = $me->id;
        $order = $request->only(['tanggal']) + ['nota' => $nota, 'state' => 'On Going', 'karyawan_id' => $karyawan_id];
        $order = \App\Order::create($order);

        if( $order ){
            // Order Place
            $places     = explode(',', $request->get('places'));
            $places     = \App\Place::whereIn('id', $places)->get();
            $orderPlaces = [];
            foreach($places as $place){
                $placeType      = $place->kategori_id; // For Redirect
                array_push($orderPlaces, [
                    'order_id'  => $order->id,
                    'place_id'  => $place->id,
                    'harga'     => $place->harga,
                ]);
            }
            \App\OrderPlace::insert($orderPlaces);

            // Order Detail & Order Detail Bahan
            $produks = Produk::with(['detail' => function($query){
                $query->join('bahans', 'produk_details.bahan_id', '=', 'bahans.id');
            }])->whereIn('id', array_keys($data_order_detail))->get();
            $orderDetailBahan = [];
            foreach($produks as $produk){
                $id = $produk->id;
                // Order Detail
                $orderDetail        = [
                    'order_id'      => $order->id,
                    'produk_id'     => $produk->id,
                    'hpp'           => CountHpp($produk), //$produk->hpp,
                    'harga_jual'    => $data_order_detail[$id]['harga'],
                    'qty'           => $data_order_detail[$id]['qty'],
                    'use_mark_up'   => $produk->use_mark_up,
                    'mark_up'       => $produk->mark_up,
                    'note'          => "",
                ];
                //echo "<pre>", print_r($orderDetail), "</pre>";
                $orderDetail = \App\OrderDetail::create($orderDetail);

                if( $produk->detail->count() ){
                    // Order Detail Bahan
                    foreach($produk->detail as $pd){
                        array_push($orderDetailBahan, [
                            'order_detail_id'   => $orderDetail->id,
                            'bahan_id'          => $pd->bahan_id,
                            'harga'             => $pd->harga,
                            'qty'               => $pd->qty,
                            'satuan'            => $pd->satuan,
                        ]);
                    }
                }
            }
            \App\OrderDetailBahan::insert($orderDetailBahan);

            if ( $request->get('readstok') ) {
                Artisan::call('bahan:count');
                Artisan::call('produk:count');
            }

            $response = [ 'code' => 1, 'message' => "Sukses simpan transaksi..." ];
        }

        return response()->json([ 'result' => $response ]);
    }

    public function changeTransaksi(Request $request)
    {
        $token = $request->get('token');
    	$me = JWTAuth::toUser($token);

        $response = [ 'code' => 0, 'message' => "Unknow error!" ];

        $id = $request->get('id');
        $data_order_detail = $request->get('data_order') != "" ? json_decode($request->get('data_order'), true) : [];
        // Convert like data session
        $temp = [];
        foreach ($data_order_detail as $d) {
            $key = $d['id'];
            $temp[$key] = $d;
        }
        $data_order_detail = $temp;
        $order = \App\Order::with('place.place')->find($id);

        if( $order->karyawan_id != $me->id ){
            $response = [ 'code' => 403, 'message' => "Ini bukan order anda!" ];
        }

        if( $response['code'] == 0 && count($data_order_detail) ){
            // Order Detail
            $orderDetailOld = \App\OrderDetail::where('order_id', $id)
                                ->whereIn('produk_id', array_keys($data_order_detail))
                                ->get();
            # Update Order Detail
            foreach($orderDetailOld as $odo){
                $oldQty     = $odo->qty;
                $updateQty  = $oldQty + $data_order_detail[$odo->produk_id]['qty'];
                $updatePrice= $data_order_detail[$odo->produk_id]['harga'];
                \App\OrderDetail::find($odo->id)->update(['qty' => $updateQty, 'harga_jual' => $updatePrice]);
                unset($data_order_detail[$odo->produk_id]);
            }
            if( count($data_order_detail) ){
                # New Order Detail
                $produks = Produk::with(['detail' => function($query){
                    $query->join('bahans', 'produk_details.bahan_id', '=', 'bahans.id');
                }])->whereIn('id', array_keys($data_order_detail))->get();
                $orderDetailBahan = [];
                foreach($produks as $produk){
                    $pId = $produk->id;
                    // Order Detail
                    $orderDetail        = [
                        'order_id'      => $id,
                        'produk_id'     => $produk->id,
                        'hpp'           => CountHpp($produk), //$produk->hpp,
                        'harga_jual'    => $data_order_detail[$pId]['harga'],
                        'qty'           => $data_order_detail[$pId]['qty'],
                        'use_mark_up'   => $produk->use_mark_up,
                        'mark_up'       => $produk->mark_up,
                        'note'          => "",
                    ];
                    //echo "<pre>", print_r($orderDetail), "</pre>";
                    $orderDetail = \App\OrderDetail::create($orderDetail);
                    if( $produk->detail->count() ){
                        // Order Detail Bahan
                        foreach($produk->detail as $pd){
                            array_push($orderDetailBahan, [
                                'order_detail_id'   => $orderDetail->id,
                                'bahan_id'          => $pd->bahan_id,
                                'harga'             => $pd->harga,
                                'qty'               => $pd->qty,
                                'satuan'            => $pd->satuan,
                            ]);
                        }
                    }
                }
                \App\OrderDetailBahan::insert($orderDetailBahan);
            }

            if ( $request->get('readstok') ) {
                Artisan::call('bahan:count');
                Artisan::call('produk:count');
            }

            $response = [ 'code' => 1, 'message' => "Sukses ubah transaksi..." ];
        }

        return response()->json([ 'result' => $response ]);
    }

    public function transaksi(Request $request)
    {
        $token = $request->get('token');
    	$me = JWTAuth::toUser($token);

        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $orders = Order::with(['karyawan', 'place.place'])
            ->where(DB::raw('SUBSTRING(tanggal, 1, 10)'), $tanggal)
            ->where('karyawan_id', $me->id)
            ->get();

        $data = [];
        $i = 0;
        foreach($orders as $order)
        {
            $i++;

            $place = "";

            foreach($order->place as $p){
                $place .= $p->place->nama.", ";
            }

            $place = rtrim($place, ", ");

            array_push($data, [
                'no'        => $i,
                'id'        => $order->id,
                'nota'      => $order->nota,
                'place'     => $place,
                'status'    => $order->state,
                'karyawan'  => $order->karyawan->nama,
                'karyawan_id' => $order->karyawan_id,
            ]);
        }

        return response()->json(['result' => $data]);
    }

    public function detail(Request $request)
    {
        if( $request->get('id') ){
            $token = $request->get('token');
        	$me = JWTAuth::toUser($token);

            $response = [ 'code' => 0, 'message' => "Unknow error!" ];

            $id = $request->get('id');

            $order = Order::with('place.place.kategori')->find($id);

            if( $order->karyawan_id != $me->id ){
                $response = [ 'code' => 403, 'message' => "Ini bukan order anda!" ];
            }else{
                $orderDetails = OrderDetail::with('order')->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                    ->join('produks', 'order_details.produk_id', '=', 'produks.id')
                    ->where('order_details.order_id', $id)
                    ->having('qty', '>', 0)
                    ->select([
                        'order_details.id', 'order_details.produk_id', 'produks.nama', 'order_details.harga_jual',
                        'order_details.qty as qty_ori', DB::raw('ifnull(order_detail_returns.qty, 0) as qty_return'),
                        DB::raw('(order_details.qty - ifnull(order_detail_returns.qty, 0))qty'),
                        DB::raw('(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))subtotal'),
                    ])->get();


                $data = [];
                $i = 0;
                foreach($orderDetails as $od)
                {
                    $i++;
                    array_push($data, [
                        'no'            => $i,
                        'nama_produk'   => $od->nama,
                        'harga'         => number_format($od->harga_jual, 0, ",", "."),
                        'qty'           => $od->qty,
                        'subtotal'      => number_format($od->subtotal, 0, ",", "."),
                    ]);
                }

                foreach($order->place as $op){
                    if( $op->harga > 0 ){
                        $i++;
                        array_push($data, [
                            'no'            => $i,
                            'nama_produk'   => "Reservasi ".$op->place->nama." - ".$op->place->kategori->nama,
                            'harga'         => number_format($op->harga, 0, ",", "."),
                            'qty'           => 1,
                            'subtotal'      => number_format($op->harga, 0, ",", "."),
                        ]);
                    }
                }

                $i++;

                if( $order->state == "Closed" ){
                    $order->load('bayar');

                    array_push($data, [
                        'no'            => $i,
                        'nama_produk'   => "Service",
                        'harga'         => number_format($order->bayar->service_cost, 0, ",", "."),
                        'qty'           => 1,
                        'subtotal'      => number_format($order->bayar->service_cost, 0, ",", "."),
                    ]);
                }/*else{
                    array_push($data, [
                        'no'            => $i,
                        'nama_produk'   => "Service",
                        'harga'         => number_format(setting()->service_cost, 0, ",", "."),
                        'qty'           => 1,
                        'subtotal'      => number_format(setting()->service_cost, 0, ",", "."),
                    ]);
                }*/

                $response = $data;
            }

            return response()->json(['result' => $response]);
        }else{
            abort(500);
        }
    }

    public function changePassword(Request $request)
    {
        $token = $request->get('token');
    	$me = JWTAuth::toUser($token);

        $response = [ 'code' => 0, 'message' => "Unknow error!" ];

        if( !Hash::check($request->get('old_password'), $me->password) ){
            $response = [ 'code' => 1, 'message' => "Password lama tidak sama!" ];
        }

        if( $code == 0 ){
            if( $me->update(['password' => Hash::make($request->get('password'))]) ){
                $response = [ 'code' => 2, 'message' => "Sukses ubah password..." ];
            }
        }

        return response()->json([ 'result' => $response ]);
    }
}
