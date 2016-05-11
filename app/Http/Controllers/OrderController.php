<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\OrderDetailBahan;
use App\OrderDetailReturn;
use App\OrderPlace;
use App\OrderCancel;
use App\OrderMerge;
use App\OrderTax;
use App\OrderBayar;
use App\OrderBayarBank;
use App\Place;
use App\PlaceKategori;
use App\Produk;
use App\Tax;
use App\Setting;
use App\Bank;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use Gate;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if( Gate::denies('order.select_place') ){
            return view(config('app.template').'.error.403');
        }

        $types  = PlaceKategori::where('active', 1)->lists('nama', 'id');
        $type   = $request->get('type') ? $request->get('type') : PlaceKategori::where('active', 1)->first()->id;
        $tgl    = $request->get('tgl') ? $request->get('tgl') : date('Y-m-d');

        if( in_array($type, array_keys($types->toArray())) ){
            $places = Place::leftJoin(DB::raw("( SELECT order_places.`place_id`, orders.`id` AS order_id, orders.`state`
                FROM order_places INNER JOIN orders ON order_places.`order_id` = orders.`id`
                WHERE SUBSTRING(orders.tanggal, 1, 10) = '".$tgl."' AND orders.`state` = 'On Going' )as order_place_temp"), function($query){
                    $query->on('places.id', '=', 'order_place_temp.place_id');
            })->where('kategori_id', $type)->where('places.active', 1)
            ->orderBy('places.id')->get();

            $data = [
                'type'      => $type,
                'tgl'       => Carbon::createFromFormat('Y-m-d', $tgl),
                'types'     => $types,
                'places'    => $places
            ];
            return view(config('app.template').'.order.table', $data);
        }else{
            return view(config('app.template').'.error.404');
        }
    }

    public function openOrder(Request $request, $id)
    {
        if( Gate::denies('order.open') ){
            return view(config('app.template').'.error.403');
        }

        if( !$request->old() ){
            $request->session()->forget('data_order');
        }

        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $data = ['current_place' => $id, 'tgl' => Carbon::createFromFormat('Y-m-d', $tanggal)];
        return view(config('app.template').'.order.open', $data);
    }

    public function saveOpenOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'       => 'required|date',
            'places'        => 'required',
            'karyawan_id'   => 'required|exists:karyawans,id',
        ], [
            'tanggal.required'  => 'Tanggal tidak boleh kosong.',
            'tanggal.date'      => 'Input harus tanggal.',
            'places.required'   => 'Tempat tidak boleh kosong',
            'karyawan_id.required'  => 'Karyawan tidak boleh kosong.',
            'karyawan_id.exists'    => 'Karyawan tidak terdaftar.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data_order_detail = $request->session()->has('data_order') ?
                        $request->session()->get('data_order') : [];

        if( !count($data_order_detail) ){
            return redirect()->back()
                ->withErrors(['no_details' => 'Tidak ada produk yang dipesan.'])
                ->withInput();
        }

        # Create Nota
        $setting = Setting::first();
        // Get Last Order
        $tanggal    = $request->get('tanggal');
        $lastOrder  = Order::where('tanggal', $tanggal)->get()->count();
        $nota       = $setting->init_kode."-".str_replace('-', '', date('dmY', strtotime($tanggal))).($lastOrder+1);

        // Order
        $karyawan_id = $request->get('karyawan_id') ? $request->get('karyawan_id') :
                            ( Auth::check() ? Auth::user()->karyawan()->id : '1' );
        $order = $request->only(['tanggal']) + ['nota' => $nota, 'state' => 'On Going', 'karyawan_id' => $karyawan_id];
        $order = Order::create($order);
        // Order Place
        $places     = explode(',', $request->get('places'));
        $places     = Place::whereIn('id', $places)->get();
        $orderPlaces = [];
        foreach($places as $place){
            $placeType      = $place->kategori_id; // For Redirect
            array_push($orderPlaces, [
                'order_id'  => $order->id,
                'place_id'  => $place->id,
                'harga'     => $place->harga,
            ]);
        }
        OrderPlace::insert($orderPlaces);
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
                'note'          => $data_order_detail[$id]['note'],
            ];
            //echo "<pre>", print_r($orderDetail), "</pre>";
            $orderDetail = OrderDetail::create($orderDetail);

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
        OrderDetailBahan::insert($orderDetailBahan);

        $request->session()->forget('data_order');

        return redirect('/order?tgl='.$tanggal.'&type='.$placeType)->with('succcess', 'Sukses simpan data order.');
    }

    public function cancelOrder($id)
    {
        if( Gate::denies('order.cancel') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'order_id'  => $id,
            'places'    => OrderPlace::join('places', 'order_places.place_id', '=', 'places.id')
                            ->where('order_id', $id)->get(),
        ];
        return view(config('app.template').'.order.cancel', $data);
    }

    public function saveCancelOrder(Request $request, $id)
    {
        if( Order::find($id)->update(['state' => 'Canceled']) ){
            $input = $request->all() + ['order_id' => $id];
            if( OrderCancel::create($input) ){
                $request->session()->flash('succcess', 'Sukses cancel order.');
                return 1;
            }
        }

        return 0;
    }

    public function reChangeOrder(Request $request, $id)
    {
        OrderTax::where('order_id', $id)->delete();
        OrderBayar::where('order_id', $id)->delete();
        OrderBayarBank::where('order_id', $id)->delete();
        Order::find($id)->update(['state' => 'On Going']);
        return redirect("/order/$id/change");
    }

    public function changeOrder(Request $request, $id)
    {
        if( Gate::denies('order.change') ){
            return view(config('app.template').'.error.403');
        }

        if( !$request->old() ){
            $request->session()->forget('order_detail_remove');
            $request->session()->forget('data_order');
        }

        $order = Order::with(['karyawan', 'detail.produk', 'detail.detailReturn', 'place'])->find($id);

        if( !$order ){
            return view(config('app.template').'.error.404');
        }

        $current_place = implode(',', array_column($order->place->toArray(), 'place_id'));

        // Detail Order
        $orderDetail = [];
        foreach($order->detail->toArray() as $od){
            $odQty = $od['qty'] - (($od['detail_return'] != null) ? $od['detail_return']['qty'] : 0);
            $in = array_only($od, ['harga_jual', 'satuan']) + [
                'nama'  => $od['produk']['nama'],
                'qty'   => $odQty,
                'subtotal' => ($od['harga_jual'] * $odQty),
            ];
            array_push($orderDetail, $in);
        }

        $data = [
            'order'         => $order,
            'orderDetail'   => $orderDetail,
            'tanggal'       => $order->tanggal->format('Y-m-d'),
            'current_place' => $current_place,
        ];

        return view(config('app.template').'.order.change', $data);
    }

    public function saveChangeOrder(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'places'    => 'required',
        ], [
            'places.required'   => 'Tempat tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Order Place
        $inputPlaces    = explode(',', $request->get('places'));
        $oldPlaces      = array_column(OrderPlace::where('order_id', $id)->get()->toArray(), 'place_id');
        # Compare For New
        $diff = array_diff($inputPlaces, $oldPlaces);
        if( count($diff) ){
            $places = Place::whereIn('id', $diff)->get();
            $orderPlaces = [];
            foreach($places as $place){
                array_push($orderPlaces, [
                    'order_id'  => $id,
                    'place_id'  => $place->id,
                    'harga'     => $place->harga,
                ]);
            }
            OrderPlace::insert($orderPlaces);
        }
        # Compare For Delete
        $diff = array_diff($oldPlaces, $inputPlaces);
        if( count($diff) ){
            OrderPlace::where('order_id', $id)
                        ->whereIn('place_id', $diff)
                        ->delete();
        }

        $data_order_detail = $request->session()->has('data_order') ?
                        $request->session()->get('data_order') : [];

        $order = Order::with('place.place')->find($id);

        if( count($data_order_detail) ){
            // Order Detail
            $orderDetailOld = OrderDetail::where('order_id', $id)
                                ->whereIn('produk_id', array_keys($data_order_detail))
                                ->get();

            # Update Order Detail
            foreach($orderDetailOld as $odo){
                $oldQty     = $odo->qty;
                $updateQty  = $oldQty + $data_order_detail[$odo->produk_id]['qty'];
                $updatePrice= $data_order_detail[$odo->produk_id]['harga'];
                OrderDetail::find($odo->id)->update(['qty' => $updateQty, 'harga_jual' => $updatePrice]);
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
                        'note'          => $data_order_detail[$pId]['note'],
                    ];
                    //echo "<pre>", print_r($orderDetail), "</pre>";
                    $orderDetail = OrderDetail::create($orderDetail);

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
                OrderDetailBahan::insert($orderDetailBahan);
            }

            $request->session()->forget('data_order');
        }

        $tglRedirect    = $order->tanggal->format('Y-m-d');
        $typeRedirect   = $order->place[0]->place->kategori_id;

        return redirect('/order?tgl='.$tglRedirect.'&type='.$typeRedirect)->with('succcess', 'Sukses ubah data order.');
    }

    public function mergeOrder(Request $request, $id)
    {
        if( Gate::denies('order.merge') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'tanggal'   => $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d'),
            'order_id'  => $id,
            'places'    => OrderPlace::join('places', 'order_places.place_id', '=', 'places.id')
                            ->where('order_id', $id)->get(),
        ];
        return view(config('app.template').'.order.merge', $data);
    }

    public function saveMergeOrder(Request $request, $id)
    {
        if( $id == $request->get('to_order_id') ){
            return 2;
        }

        $currentOrderDetail = OrderDetail::with('detailBahan')->where('order_id', $id)->get();

        foreach($currentOrderDetail as $cod){
            $toOrderDetail = OrderDetail::where('order_id', $request->get('to_order_id'))
                                ->where('produk_id', $cod->produk_id);

            if( $toOrderDetail->count() ){
                // Update Qtys
                $oldQty     = $toOrderDetail->first()->qty;
                $updateQty  = $oldQty + $cod->qty;
                $toOrderDetail->first()->update(['qty' => $updateQty]);
            }else{
                // New Data
                $in = array_except($cod->toArray(), ['id', 'order_id', 'detail_bahan']) + ['order_id' => $request->get('to_order_id')];
                $odn = OrderDetail::create($in);
                $detailBahan = array_get($cod->toArray(), 'detail_bahan');
                $in = [];
                foreach($detailBahan as $db){
                    array_push($in, ( array_except($db, ['id', 'order_detail_id']) + ['order_detail_id' => $odn->id] ));
                }
                OrderDetailBahan::insert($in);
            }
        }

        if( OrderMerge::insert(['order_id' => $id, 'to_order_id' => $request->get('to_order_id')]) ){
            if( $request->get('use_place') == '1' ){
                $orderPlace = OrderPlace::where('order_id', $id)->get();
                $temp       = [];
                foreach($orderPlace as $op){
                    array_push($temp, [
                        'order_id'  => $request->get('to_order_id'),
                        'place_id'  => $op->place_id,
                        'harga'     => $op->harga,
                    ]);
                }
                OrderPlace::insert($temp);
            }

            if( Order::find($id)->update(['state' => 'Merged']) ){
                return 1;
            }
        }

        return 0;
    }

    public function closeOrder($id)
    {
        if( Gate::denies('order.close') ){
            return view(config('app.template').'.error.403');
        }

        $order = Order::with(['karyawan', 'detail.produk', 'detail.detailReturn', 'place.place.kategori'])->find($id);

        if( !$order ){
            return view(config('app.template').'.error.404');
        }

        // Detail Order
        $orderDetail = [];
        foreach($order->detail->toArray() as $od){
            $odQty = $od['qty'] - (($od['detail_return'] != null) ? $od['detail_return']['qty'] : 0);
            $in = array_only($od, ['harga_jual', 'satuan']) + [
                'nama'  => $od['produk']['nama'],
                'qty'   => $odQty,
                'subtotal' => ($od['harga_jual'] * $odQty),
            ];
            array_push($orderDetail, $in);
        }

        $current_place  = implode(', ', array_column(array_column($order->place->toArray(), 'place'), 'nama'));

        $orderPlaces = [];
        foreach($order->place as $op){
            if( $op->harga > 0 ){
                array_push($orderPlaces, [
                    'nama'  => $op->place->nama.' - '.$op->place->kategori->nama,
                    'harga' => $op->harga,
                ]);
            }
        }

        $data = [
            'order'         => $order,
            'orderDetail'   => $orderDetail,
            'orderPlaces'   => $orderPlaces,
            'tanggal'       => $order->tanggal->format('Y-m-d'),
            'current_place' => $current_place,
            'init_tax'      => Tax::where('active', 1)->first(),
            'taxes'         => Tax::where('active', 1)->lists('type', 'id'),
            'banks'         => Bank::where('active', 1)->lists('nama_bank', 'id'),
        ];

        return view(config('app.template').'.order.close', $data);
    }

    public function saveCloseOrder(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'   => 'required|date',
            'bayar'     => 'required|numeric',
        ], [
            'tanggal.required'  => 'Tanggal tidak boleh kosong.',
            'tanggal.bayar'     => 'Input harus tanggal.',
            'bayar.required'    => 'Bayar tidak boleh kosong.',
            'bayar.numeric'     => 'Input harus angka.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $orderTax       = [
            'order_id'  => $id,
            'tax_id'    => $request->get('tax_id'),
            'procentage'=> $request->get('tax_procentage'),
        ];

        if( OrderTax::create($orderTax) ){
            $orderBayar = [
                'order_id'      => $id,
                'karyawan_id'   => ( Auth::check() ? Auth::user()->karyawan->id : '1' ),
                'service_cost'  => $request->get('service_cost'),
                'diskon'        => ( $request->get('diskon') != '' ? $request->get('diskon') : 0 ),
                'bayar'         => $request->get('bayar'),
                'type_bayar'    => $request->get('type_bayar'),
            ];

            if( OrderBayar::create($orderBayar) ){
                if( $request->get('type_bayar') == 'debit' || $request->get('type_bayar') == 'credit_card' ){
                    $orderBayarBank = [
                        'order_id'  => $id,
                        'bank_id'   => $request->get('bank_id'),
                    ];

                    $orderBayarBank['tax_procentage'] = $request->get('tax_bayar_procentage');
                    OrderBayarBank::create($orderBayarBank);
                }

                $order = ['state' => 'Closed'];
                if( $request->get('customer_id') != "" ){
                    $order['customer_id'] = $request->get('customer_id');
                }

                if( Order::find($id)->update($order) ){
                    return redirect('/order/pertanggal/detail?id='.$id)
                        ->with('succcess', 'Sukses Tutup Order');
                }
            }
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal Tutup Order !!!']);withInput();
    }

    public function pertanggal(Request $request)
    {
        if( Gate::denies('order.list') ){
            return view(config('app.template').'.error.403');
        }

        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $orders = Order::with(['karyawan', 'bayar.karyawan'])->where(DB::raw('SUBSTRING(tanggal, 1, 10)'), $tanggal)->get();

        $data = [
            'tanggal' => Carbon::parse($tanggal),
            'orders' => $orders,
        ];

        return view(config('app.template').'.order.pertanggal', $data);
    }

    public function pertanggalDetail(Request $request)
    {
        if( Gate::denies('order.list.detail') ){
            return view(config('app.template').'.error.403');
        }

        if( $request->get('id') ){
            $id = $request->get('id'); // order_id

            $order = Order::with('customer', 'bayar.karyawan', 'tax.tax', 'bayarBank.bank', 'place.place.kategori')->find($id);

            if( !$order ){
                return view(config('app.template').'.error.404');
            }

            $orderDetail = OrderDetail::with('order')->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->join('produks', 'order_details.produk_id', '=', 'produks.id')
                ->where('order_details.order_id', $id)->select([
                    'order_details.id', 'order_details.produk_id', 'produks.nama', 'order_details.harga_jual',
                    'order_details.qty as qty_ori', DB::raw('ifnull(order_detail_returns.qty, 0) as qty_return'),
                    DB::raw('(order_details.qty - ifnull(order_detail_returns.qty, 0))qty'),
                    DB::raw('(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))subtotal'),
                ])->get();

            $orderPlaces = [];
            foreach($order->place as $op){
                if( $op->harga > 0 ){
                    array_push($orderPlaces, [
                        'nama'  => $op->place->nama.' - '.$op->place->kategori->nama,
                        'harga' => $op->harga,
                    ]);
                }
            }

            $data = [
                'orderDetail' => $orderDetail,
                'orderPlaces' => $orderPlaces,
                'order' => $order,
                'id' => $id,
            ];

            return view(config('app.template').'.order.pertanggal-detail', $data);

        }else{
            return view(config('app.template').'.error.404');
        }
    }

    public function savePertanggalDetail(Request $request)
    {
        $id     = $request->get('id');
        $diskon = ( $request->get('diskon') != "" && $request->get('diskon') != " " ) ? $request->get('diskon') : 0;

        if( OrderBayar::where('order_id', $id)->update(['diskon' => $diskon]) ){
            return redirect()->back()->with('succcess', 'Sukses simpan data pembayaran.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data pembayaran.']);
    }

    public function pertanggalReturn(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $orders = Order::with('karyawan')->whereHas('detail', function($query){
            $query->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->whereNotNull('order_detail_returns.id');
        })->where(DB::raw('SUBSTRING(tanggal, 1, 10)'), $tanggal)->get();

        $data = [
            'tanggal' => Carbon::parse($tanggal),
            'orders' => $orders,
        ];

        return view(config('app.template').'.order.return-pertanggal', $data);
    }

    public function pertanggalReturnDetail(Request $request)
    {
        $orderDetail = OrderDetail::join('produks', 'order_details.produk_id', '=', 'produks.id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where('order_id', $request->get('id'))
            ->whereNotNull('order_detail_returns.id')
            ->select(['produks.nama', DB::raw('order_detail_returns.qty as qty_return')])
            ->get();

        $data = ['orderDetail' => $orderDetail, 'order' => Order::find($request->get('id'))];
        return view(config('app.template').'.order.return-pertanggal-detail', $data);
    }

    public function pertanggalDetailReturn(Request $request)
    {
        if( $request->get('id') ){
            $id = $request->get('id');
            $orderDetail = OrderDetail::with('order', 'detailReturn')->find($id);
            $data = ['orderDetail' => $orderDetail];
            return view(config('app.template').'.order.pertanggal-detail-return', $data);
        }else{
            return view(config('app.template').'.error.404');
        }
    }

    public function savePertanggalDetailReturn(Request $request)
    {
        $id = $request->get('id');
        $qty = $request->get('qty');

        $odr = OrderDetailReturn::where('order_detail_id', $id);
        if($odr->count()){
            if( $qty > 0 ){
                if( $odr->update(['qty' => $qty]) ){
                    return 1;
                }
            }else{
                if( $odr->delete() ){
                    return 1;
                }
            }
        }else{
            if( OrderDetailReturn::create(['order_detail_id' => $id, 'qty' => $qty]) ){
                return 1;
            }
        }

        return 0;
    }

    /* Session and Ajax Actions */
    public function showProduk(Request $request)
    {
        return $request->session()->get('data_order');
    }

    # Add produk
    public function saveProduk(Request $request)
    {
        $session = $request->session();
        // Check Stok
        $produkId   = $request->get('id');
        $qty        = $request->get('qty');
        $produk = Produk::with('detail')->find($produkId);

        $denied = false; // allow transaction with >=0 stok
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
            $saveSession = $request->only(['id', 'qty', 'harga', 'note']); // id as produk_id
            $session->put('data_order.'.$request->get('id'), $saveSession);
            return [
                'num'       => $request->get('num'),
                'nama'      => $request->get('nama'),
                'harga'     => number_format($request->get('harga'), 0, ',', '.'),
                'qty'       => $request->get('qty'),
                'subtotal'  => number_format($request->get('harga') * $request->get('qty'), 0, ',', '.'),
                'note'      => $request->get('note'),
                'action'    => $request->get('action'),
            ];
        }else{
            return 0;
        }
    }

    public function removeProduk(Request $request)
    {
        $session = $request->session();
        $session->forget('data_order.'.$request->get('id'));
    }
    # End Add produk

    # Remove Produk Detail
    public function removeDetailProdukSessionShow(Request $request)
    {
        return $request->session()->get('order_detail_remove');
    }

    public function removeDetailProdukSession(Request $request)
    {
        if( $request->session()->has('order_detail_remove') ){
            $request->session()->push('order_detail_remove', $request->get('id'));
        }else{
            $request->session()->put('order_detail_remove', [ $request->get('id') ]);
        }
    }

    # Search Produk
    public function getProduk(Request $request)
    {
        $produks = Produk::with(['detail' => function($query){
            $query->join('bahans', 'produk_details.bahan_id', '=', 'bahans.id');
        }])->where('active', 1)->where('nama', 'like', '%'.$request->get('q').'%')->get();

        $data = [];
        foreach($produks as $produk)
        {
            array_push($data, [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'satuan' => $produk->satuan,
                'harga' => CountPrice($produk),
            ]);
        }

        return $data;
    }

    # Search Place
    public function getPlace(Request $request)
    {
        if( $request->get('id') ){
            return Place::with('kategori')->where('active', 1)->where('id', $request->get('id'))->first();
        }elseif($request->get('ids')){
            return Place::with('kategori')->where('active', 1)->whereIn('id', explode('+', $request->get('ids')))->get();
        }else{
            $tgl    = $request->get('date') ? $request->get('date') : date('Y-m-d');
            $tgl    = ( $tgl != "" ) ? $tgl : date('Y-m-d');
            $ready  = $request->get('ready') ? $request->get('ready') : 'Ya';
            $places = Place::leftJoin(DB::raw("( SELECT order_places.`place_id`, orders.`id` AS order_id, orders.`state`
                FROM order_places INNER JOIN orders ON order_places.`order_id` = orders.`id`
                WHERE SUBSTRING(orders.tanggal, 1, 10) = '".$tgl."' AND orders.`state` = 'On Going' )as order_place_temp"), function($query){
                    $query->on('places.id', '=', 'order_place_temp.place_id');
            });

            if( $ready == 'Ya' ){
                $places = $places->whereNull('order_place_temp.order_id');
            }else{
                $places = $places->whereNotNull('order_place_temp.order_id');
            }

            $places = $places->where('nama', 'like', '%'.$request->get('q').'%')
                        ->where('active', 1)->with('kategori')->get();

            return $places;
        }
    }
    /* End Session and Ajax Actions */
}
