<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\Setting;
use App\User;
use App\Produk;
use App\Karyawan;
use DB;
use Hash;
use Auth;

class ApiController extends Controller
{
    public function index(Request $request) // as Login
    {
        \Debugbar::disable();

        $credentials = [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'active' => 1,
        ];

        if( Auth::once($credentials) ){
            $user = User::where('users.active', 1)->where('username', $request->get('username'))->first();
            return $user->api_token;
        }

        return 0;
    }

    public function user(Request $request)
    {
        $user = auth()->guard('api')->user();

        return [
            'user_id'       => $user->id,
            'username'      => $user->username,
            'nama_karyawan' => $user->karyawan->nama,
            'karyawan_id'   => $user->karyawan->id,
            'api_token'     => $request->get('api_token'),
        ];
    }

    public function karyawan()
    {
        $karyawans = Karyawan::where('active', 1)->get();

        $data = [];
        foreach($karyawans as $karyawan)
        {
            array_push($data, [
                'karyawan_id' => $karyawan->id,
                'nama_karyawan'  => $karyawan->nama,
            ]);
        }

        $display['karyawan'] = $data;

        return $display;
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

        $display['produk'] = $data;

        return $display;
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

        $display['place'] = $data;

        return $display;
    }

    public function tax()
    {
        $taxs = \App\Tax::where('active', 1)->get();

        $data = [];
        foreach($taxs as $tax)
        {
            array_push($data, [
                'tax_id' => $tax->id,
                'type' => $tax->type,
                'procentage' => $tax->procentage,
            ]);
        }

        $display['tax'] = $data;

        return $display;
    }

    public function bank()
    {
        $banks = \App\Bank::with('tax')->where('active', 1)->get();

        $data = [];
        foreach($banks as $bank)
        {
            $row = [
                'bank_id' => $bank->id,
                'nama_bank' => $bank->nama_bank,
            ];

            foreach($bank->tax as $bt){
                $type = 'tax_'.$bt->type;
                $row[$type] = $bt->tax;
            }

            array_push($data, $row);
        }

        $display['bank'] = $data;

        return $display;
    }

    public function customer()
    {
        $customers = \App\Customer::whereNotNull('customers.nama')->get();

        $data = [];
        foreach($customers as $customer)
        {
            array_push($data, [
                'customer_id'   => $customer->id,
                'customer_code' => $customer->kode,
                'nama_customer' => $customer->nama,
            ]);
        }

        $display['customers'] = $data;

        return $display;
    }

    public function composite(Request $request)
    {
        $produkId   = $request->get('id');
        $qty        = $request->get('qty') ? $request->get('qty') : 1;
        $produk     = Produk::with('detail.bahan')->where('active', 1)->where('id', $produkId)->first();
        $data       = [];

        if( $produk->detail->count() ){
            $tempBahan = [];
            foreach( $produk->detail as $pd ){
                $bId = $pd['bahan_id'];
                $tempBahan[$bId] = [
                    'nama'  => $pd['bahan']['nama'],
                    'qty'   => round($pd['qty'], 2),
                    'req'   => $qty,
                    'total' => round(( round($pd['qty'], 2) * $qty ), 2),
                ];
            }

            $bahans = \App\Bahan::stok()->whereIn('bahans.id', array_keys($tempBahan))->get();
            $i = 0;
            foreach($bahans as $bahan){
                $i++;
                $bId = $bahan->id;
                $txt = "Cukup";
                if( $bahan->sisa_stok < $tempBahan[$bId]["total"] ){
                    $txt = "Tidak Cukup";
                }

                $data[] = ["no" => $i] + $tempBahan[$bId] + ["stok" => round($bahan->sisa_stok, 2), "state" => $txt];
            }
        }else{
            $temp = [
                'no'    => 1,
                'nama'  => $produk['nama'],
                'qty'   => 1,
                'req'   => $qty,
                'total' => $qty,
            ];

            $produk = Produk::stok()->find($produkId);
            $txt = "Cukup";
            if( $produk->sisa_stok < $qty ){
                $txt = "Tidak Cukup";
            }

            $temp += ["stok" => round($produk->sisa_stok, 2), "state" => $txt];
            $data[] = $temp;
        }

        $display['komposisi_produk'] = $data;

        return $display;
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

            $bahans = \App\Bahan::stok()->whereIn('bahans.id', array_keys($tempBahan))->get();
            foreach($bahans as $bahan){
                $bId = $bahan->id;
                if( $bahan->sisa_stok < $tempBahan[$bId] ){
                    $denied = true;
                }
            }
        }else{
            $produk = Produk::stok()->find($produkId);
            if( $produk->sisa_stok < $qty ){
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
        \Debugbar::disable();

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
        $karyawan_id = $request->get('karyawan_id');
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

            return 1;
        }

        return 0;
    }

    public function changeTransaksi(Request $request)
    {
        \Debugbar::disable();
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
        if( count($data_order_detail) ){
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
        }
        return 1;
    }

    public function closeTransaksi(Request $request)
    {
        \Debugbar::disable();

        $id = $request->get('id');

        $orderTax       = [
            'order_id'  => $id,
            'tax_id'    => $request->get('tax_id'),
            'procentage'=> $request->get('tax_procentage'),
        ];

        if( \App\OrderTax::create($orderTax) ){
            $orderBayar = [
                'order_id'      => $id,
                'karyawan_id'   => ( Auth::guard('api')->check() ? Auth::guard('api')->user()->karyawan->id : '1' ),
                'service_cost'  => $request->get('service_cost'), //setting()->service_cost,
                'diskon'        => ( $request->get('diskon') != '' ? $request->get('diskon') : 0 ),
                'bayar'         => $request->get('bayar'),
                'type_bayar'    => $request->get('type_bayar'),
            ];

            if( \App\OrderBayar::create($orderBayar) ){
                if( $request->get('type_bayar') == 'debit' || $request->get('type_bayar') == 'credit_card' ){
                    $orderBayarBank = [
                        'order_id'  => $id,
                        'bank_id'   => $request->get('bank_id'),
                    ];

                    $orderBayarBank['tax_procentage'] = $request->get('tax_bayar_procentage');
                    \App\OrderBayarBank::create($orderBayarBank);
                }

                $order = ['state' => 'Closed'];
                if( $request->get('customer_id') != "" ){
                    $order['customer_id'] = $request->get('customer_id');
                }

                if( \App\Order::find($id)->update($order) ){
                    return 1;
                }
            }
        }

        return 0;
    }

    public function transaksi(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $orders = Order::with(['karyawan', 'place.place'])->where(DB::raw('SUBSTRING(tanggal, 1, 10)'), $tanggal)->get();

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

        $display['penjualan'] = $data;

        return $display;
    }

    public function detail(Request $request)
    {
        if( $request->get('id') ){
            $id = $request->get('id');

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

            $order = Order::with('place.place.kategori')->find($id);

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

            $display['detail_penjualan'] = $data;

            return $display;
        }else{
            abort(500);
        }
    }

    public function bayar(Request $request)
    {
        if( $request->get('id') ){
            $id = $request->get('id');

            $order = Order::with(['karyawan', 'bayar.karyawan', 'tax.tax',
                'bayarBank.bank', 'place.place'])->find($id);

            $total = $orderDetails = OrderDetail::with('order')->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->join('produks', 'order_details.produk_id', '=', 'produks.id')
                ->where('order_details.order_id', $id)->select([
                    'order_details.id', 'order_details.produk_id', 'produks.nama', 'order_details.harga_jual',
                    'order_details.qty as qty_ori', DB::raw('ifnull(order_detail_returns.qty, 0) as qty_return'),
                    DB::raw('(order_details.qty - ifnull(order_detail_returns.qty, 0))qty'),
                    DB::raw('(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))subtotal'),
                ])->get()->sum('subtotal');

            foreach($order->place as $op){
                if( $op->harga > 0 ){
                    $total += $op->harga;
                }
            }

            $total += $order->bayar->service_cost;

            $tax_procentage = round($order->tax->procentage);
            $tax            = round($total * ( $tax_procentage / 100 ));
            $tax_bayar_procentage = ( $order->bayarBank != null ) ? round($order->bayarBank->tax_procentage) : 0;
            $tax_bayar  = round(( $total + $tax ) * ( $tax_bayar_procentage / 100 ));
            $jumlah     = round($total + $tax + $tax_bayar);
            $sisa       = round($jumlah - $order->bayar->diskon);
            $kembali    = round($order->bayar->bayar - $sisa);

            return [
                'kasir'         => $order->bayar->karyawan->nama,
                'waiters'       => $order->karyawan->nama,
                'total'         => number_format($total, 0, ",", "."),
                'tax_pro'       => $order->tax->procentage,
                'tax'           => number_format($tax, 0, ",", "."),
                'tax_bayar_pro' => $tax_bayar_procentage,
                'tax_bayar'     => number_format($tax_bayar, 0, ",", "."),
                'jumlah'        => number_format($jumlah, 0, ",", "."),
                'diskon'        => number_format($order->bayar->diskon, 0, ",", "."),
                'sisa'          => number_format($sisa, 0, ",", "."),
                'bayar'         => number_format($order->bayar->bayar, 0, ",", "."),
                'kembali'       => number_format($kembali, 0, ",", "."),
            ];

        }else{
            abort(500);
        }
    }

    public function setting()
    {
        return Setting::first();
    }

    public function changePassword(Request $request)
    {
        \Debugbar::disable();

        $user = auth()->guard('api')->user();

        if( !Hash::check($request->get('old_password'), $user->password) ){
            return 1;
        }

        if( $user->update(['password' => Hash::make($request->get('password'))]) ){
            return 2;
        }

        return 0;
    }
}
