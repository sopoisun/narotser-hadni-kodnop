<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\Produk;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /* Pertanggal */
    public function index(Request $request) //pertanggal
    {
        return redirect('/report/pertanggal');
    }

    public function pertanggal(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $reports    = Order::ReportByDate($tanggal);
        $reports    = ConvertRawQueryToArray($reports);

        $data = [
            'tanggal'   => Carbon::parse($tanggal),
            'reports'   => $reports,
        ];

        return view(config('app.template').'.report.pertanggal', $data);
    }

    public function detail($id)
    {
        $order = Order::find($id);

        if( $order->state == 'Closed' ){
            $order->load('tax', 'bayarBank', 'bayar', 'place.place');

            $orderDetail = OrderDetail::join('produks', 'order_details.produk_id', '=', 'produks.id')
                ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('orders.id', $id)
                ->select([
                    'produks.nama', 'order_details.harga_jual',
                    DB::raw("(order_details.qty - ifnull(order_detail_returns.qty, 0)) as qty"),
                    DB::raw("(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0))) as subtotal")
                ])
                ->groupBy('produks.id')
                ->get();

            $orderPlaces = $order->place;
        }else{
            $order->load('merge.orderRef');
        }

        $data = [
            'order'         => $order,
            'orderDetail'   => isset($orderDetail) ? $orderDetail : [],
            'orderPlaces'   => isset($orderPlaces) ? $orderPlaces : [],
        ];
        return view(config('app.template').'.report.pertanggal-detail', $data);
    }

    public function soldItem(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            IF(order_details.`use_mark_up` = 'Tidak', order_details.`hpp`, SUM(order_detail_bahans.`harga` * ( order_detail_bahans.`qty`)))hpp,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_bahans ON order_details.id = order_detail_bahans.`order_detail_id`
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE orders.`tanggal` = '$tanggal'
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->groupBy('produks.id')
            ->select(['produks.id', 'produks.nama',
                DB::raw('ifnull((SUM(temp_order_produks.hpp)/COUNT(produks.id)), 0)hpp'),
                DB::raw('ifnull(ROUND(SUM(temp_order_produks.harga_jual)/COUNT(produks.id)), 0)harga_jual'),
                DB::raw('ifnull(SUM(temp_order_produks.qty), 0)terjual'),
                DB::raw('ifnull(SUM(temp_order_produks.hpp * temp_order_produks.qty), 0)AS total_hpp'),
                DB::raw('ifnull(SUM(temp_order_produks.harga_jual * temp_order_produks.qty), 0)AS subtotal'),
                DB::raw('ifnull((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)), 0)AS laba'),
                DB::raw('ifnull(ROUND(((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)) /
                    SUM(temp_order_produks.hpp * temp_order_produks.qty))*100), 0)laba_procentage'),
            ])
            ->get();

        $data = [
            'tanggal'   => Carbon::parse($tanggal),
            'produks'   => $produk
        ];
        return view(config('app.template').'.report.pertanggal-solditem', $data);
    }

    public function karyawan(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where('orders.tanggal', $tanggal)
            ->where('state', 'Closed')
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        $data = [
            'tanggal'   => Carbon::parse($tanggal),
            'karyawans' => $karyawans,
        ];

        return view(config('app.template').'.report.pertanggal-karyawan', $data);
    }

    public function karyawanDetail(Request $request)
    {
        if( $request->get('karyawan_id') ){
            if( $request->get('tanggal') || $request->get('bulan') || $request->get('tahun') ){
                $karyawan_id    = $request->get('karyawan_id');
                $orderProduks   = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                    ->join('produks', 'order_details.produk_id', '=', 'produks.id')
                    ->where('orders.state', 'Closed')
                    ->where('orders.karyawan_id', $karyawan_id)
                    ->groupBy('order_details.produk_id')
                    ->select(['produks.nama', 'order_details.harga_jual', DB::raw('(order_details.qty - ifnull(order_detail_returns.qty, 0))qty'),
                        DB::raw('SUM(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))total_penjualan')
                    ]);

                if( $request->get('tanggal') ){
                    $tanggal = $request->get('tanggal');
                    if( $request->get('to_tanggal') ){
                        $to_tanggal     = $request->get('to_tanggal');
                        $orderProduks   = $orderProduks->whereBetween('orders.tanggal', [$tanggal, $to_tanggal])->get();
                    }else{
                        $orderProduks   = $orderProduks->where('orders.tanggal', $tanggal)->get();
                    }
                }elseif( $request->get('bulan') ){
                    $bulan = $request->get('bulan');
                    $orderProduks = $orderProduks->where(DB::raw('SUBSTRING(orders.tanggal, 1, 7)'), $bulan)->get();
                }else{
                    $tahun = $request->get('tahun');
                    $orderProduks = $orderProduks->where(DB::raw('SUBSTRING(orders.tanggal, 1, 4)'), $tahun)->get();
                }

                $data = [
                    'orderProduks'  => $orderProduks,
                    'karyawan'      => \App\Karyawan::find($karyawan_id),
                ];

                return view(config('app.template').'.report.pertanggal-karyawan-detail', $data);
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function labaRugi(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $penjualans = Order::ReportGroup("orders.`tanggal` = '$tanggal'", "GROUP BY tanggal");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->where('account_saldos.tanggal', $tanggal)
            ->whereNull('account_saldos.relation_id')
            ->groupBy('account_id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        $data = ['tanggal' => Carbon::createFromFormat('Y-m-d', $tanggal), 'tableTemp' => $tableTemp];
        return view(config('app.template').'.report.pertanggal-labarugi', $data);
    }
    /* End Pertanggal */

    /* Periode */
    public function soldItemPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            IF(order_details.`use_mark_up` = 'Tidak', order_details.`hpp`, SUM(order_detail_bahans.`harga` * ( order_detail_bahans.`qty`)))hpp,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_bahans ON order_details.id = order_detail_bahans.`order_detail_id`
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE (orders.`tanggal` BETWEEN '$tanggal' AND '$to_tanggal' )
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->groupBy('produks.id')
            ->select(['produks.id', 'produks.nama',
                DB::raw('ifnull((SUM(temp_order_produks.hpp)/COUNT(produks.id)), 0)hpp'),
                DB::raw('ifnull(ROUND(SUM(temp_order_produks.harga_jual)/COUNT(produks.id)), 0)harga_jual'),
                DB::raw('ifnull(SUM(temp_order_produks.qty), 0)terjual'),
                DB::raw('ifnull(SUM(temp_order_produks.hpp * temp_order_produks.qty), 0)AS total_hpp'),
                DB::raw('ifnull(SUM(temp_order_produks.harga_jual * temp_order_produks.qty), 0)AS subtotal'),
                DB::raw('ifnull((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)), 0)AS laba'),
                DB::raw('ifnull(ROUND(((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)) /
                    SUM(temp_order_produks.hpp * temp_order_produks.qty))*100), 0)laba_procentage'),
            ])
            ->get();

        $data = [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'produks'   => $produk
        ];
        return view(config('app.template').'.report.periode-solditem', $data);
    }

    public function karyawanPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->whereBetween('orders.tanggal', [$tanggal, $to_tanggal])
            ->where('state', 'Closed')
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        $data = [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'karyawans' => $karyawans,
        ];

        return view(config('app.template').'.report.periode-karyawan', $data);
    }

    public function labaRugiPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $penjualans = Order::ReportGroup("(orders.`tanggal` BETWEEN '$tanggal' AND '$to_tanggal')", "");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->whereBetween('account_saldos.tanggal', [$tanggal, $to_tanggal])
            ->whereNull('account_saldos.relation_id')
            ->groupBy('account_id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        $data = [
            'tanggal'       => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'    => Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'tableTemp'     => $tableTemp
        ];
        return view(config('app.template').'.report.periode-labarugi', $data);
    }
    /* End Periode */

    /* Perbulan */
    public function perbulan(Request $request)
    {
        $bulan      = $request->get('bulan') ? $request->get('bulan') : date('Y-m');
        $start      = Carbon::parse($bulan)->startOfMonth();
        $end        = Carbon::parse($bulan)->endOfMonth();

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        $reports = Order::ReportGroup("SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan'", "GROUP BY tanggal");
        $reports = ConvertRawQueryToArray($reports);

        $data = [
            'tanggal'   => Carbon::parse($bulan),
            'dates'     => $dates,
            'reports'   => $reports,
        ];

        return view(config('app.template').'.report.perbulan', $data);
    }

    public function soldItemPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            IF(order_details.`use_mark_up` = 'Tidak', order_details.`hpp`, SUM(order_detail_bahans.`harga` * ( order_detail_bahans.`qty`)))hpp,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_bahans ON order_details.id = order_detail_bahans.`order_detail_id`
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan'
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->groupBy('produks.id')
            ->select(['produks.id', 'produks.nama',
                DB::raw('ifnull((SUM(temp_order_produks.hpp)/COUNT(produks.id)), 0)hpp'),
                DB::raw('ifnull(ROUND(SUM(temp_order_produks.harga_jual)/COUNT(produks.id)), 0)harga_jual'),
                DB::raw('ifnull(SUM(temp_order_produks.qty), 0)terjual'),
                DB::raw('ifnull(SUM(temp_order_produks.hpp * temp_order_produks.qty), 0)AS total_hpp'),
                DB::raw('ifnull(SUM(temp_order_produks.harga_jual * temp_order_produks.qty), 0)AS subtotal'),
                DB::raw('ifnull((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)), 0)AS laba'),
                DB::raw('ifnull(ROUND(((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)) /
                    SUM(temp_order_produks.hpp * temp_order_produks.qty))*100), 0)laba_procentage'),
            ])
            ->get();

        $data = [
            'tanggal'   => Carbon::parse($bulan),
            'produks'   => $produk
        ];
        return view(config('app.template').'.report.perbulan-solditem', $data);
    }

    public function karyawanPerbulan(Request $request)
    {
        $bulan      = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where(DB::raw('SUBSTRING(orders.tanggal, 1, 7)'), $bulan)
            ->where('state', 'Closed')
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        $data = [
            'tanggal'   => Carbon::parse($bulan),
            'karyawans' => $karyawans,
        ];

        return view(config('app.template').'.report.perbulan-karyawan', $data);
    }

    public function labaRugiPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $penjualans = Order::ReportGroup("SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan'", "GROUP BY SUBSTRING(tanggal, 1, 7)");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->where(DB::raw('SUBSTRING(account_saldos.tanggal, 1, 7)'), $bulan)
            ->whereNull('account_saldos.relation_id')
            ->groupBy('account_id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        $data = ['tanggal' => Carbon::createFromFormat('Y-m', $bulan), 'tableTemp' => $tableTemp];
        return view(config('app.template').'.report.perbulan-labarugi', $data);
    }
    /* End Perbulan */

    /* Pertahun */
    public function pertahun(Request $request)
    {
        $tahun      = $request->get('tahun') ? $request->get('tahun') : date('Y');
        $start      = Carbon::createFromFormat('Y', $tahun)->startOfYear();
        $end        = Carbon::createFromFormat('Y', $tahun)->endOfYear();

        $months = [];
        while ($start->lte($end)) {
            $months[] = $start->copy();
            $start->addMonth();
        }

        $reports = Order::ReportGroup("SUBSTRING(orders.`tanggal`, 1, 4) = '$tahun'", "GROUP BY SUBSTRING(tanggal, 1, 7)", "SUBSTRING(tanggal, 1, 7)as bulan");
        $reports = ConvertRawQueryToArray($reports);

        $data = [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'months'    => $months,
            'reports'   => $reports,
        ];

        return view(config('app.template').'.report.pertahun', $data);
    }

    public function soldItemPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            IF(order_details.`use_mark_up` = 'Tidak', order_details.`hpp`, SUM(order_detail_bahans.`harga` * ( order_detail_bahans.`qty`)))hpp,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_bahans ON order_details.id = order_detail_bahans.`order_detail_id`
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE SUBSTRING(orders.`tanggal`, 1, 4) = '$tahun'
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->groupBy('produks.id')
            ->select(['produks.id', 'produks.nama',
                DB::raw('ifnull((SUM(temp_order_produks.hpp)/COUNT(produks.id)), 0)hpp'),
                DB::raw('ifnull(ROUND(SUM(temp_order_produks.harga_jual)/COUNT(produks.id)), 0)harga_jual'),
                DB::raw('ifnull(SUM(temp_order_produks.qty), 0)terjual'),
                DB::raw('ifnull(SUM(temp_order_produks.hpp * temp_order_produks.qty), 0)AS total_hpp'),
                DB::raw('ifnull(SUM(temp_order_produks.harga_jual * temp_order_produks.qty), 0)AS subtotal'),
                DB::raw('ifnull((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)), 0)AS laba'),
                DB::raw('ifnull(ROUND(((SUM(temp_order_produks.harga_jual * temp_order_produks.qty) - SUM(temp_order_produks.hpp * temp_order_produks.qty)) /
                    SUM(temp_order_produks.hpp * temp_order_produks.qty))*100), 0)laba_procentage'),
            ])
            ->get();

        $data = [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'produks'   => $produk
        ];
        return view(config('app.template').'.report.pertahun-solditem', $data);
    }

    public function karyawanPertahun(Request $request)
    {
        $tahun      = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where(DB::raw('SUBSTRING(orders.tanggal, 1, 4)'), $tahun)
            ->where('state', 'Closed')
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * ( order_details.`qty` - ifnull(order_detail_returns.qty, 0) ) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        $data = [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'karyawans' => $karyawans,
        ];

        return view(config('app.template').'.report.pertahun-karyawan', $data);
    }

    public function labaRugiPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $penjualans = Order::ReportGroup("SUBSTRING(orders.`tanggal`, 1, 4) = '$tahun'", "GROUP BY SUBSTRING(tanggal, 1, 4)");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->where(DB::raw('SUBSTRING(account_saldos.tanggal, 1, 4)'), $tahun)
            ->whereNull('account_saldos.relation_id')
            ->groupBy('account_id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        $data = ['tanggal' => Carbon::createFromFormat('Y', $tahun), 'tableTemp' => $tableTemp];
        return view(config('app.template').'.report.pertahun-labarugi', $data);
    }
    /* End Pertahun */

    private function buildLabaRugiTable($data)
    {
        $penjualans     = $data['penjualans'];
        $accountSaldo   = $data['account_saldo'];

        $tableTemp = [];

        // Pendapatan
        if( count($penjualans) ){
            $penjualans = $penjualans[0];

            array_push($tableTemp, [
                'keterangan'    => 'Total Penjualan',
                'nominal'       => $penjualans['total_penjualan'],
                'sum'           => $penjualans['total_penjualan'],
                'type'          => 'pendapatan',
            ]);

            array_push($tableTemp, [
                'keterangan'    => 'Total Reservasi',
                'nominal'       => $penjualans['total_reservasi'],
                'sum'           => $penjualans['total_reservasi'],
                'type'          => 'pendapatan',
            ]);

            array_push($tableTemp, [
                'keterangan'    => 'Total Pajak',
                'nominal'       => $penjualans['pajak'],
                'sum'           => $penjualans['pajak'],
                'type'          => 'pendapatan',
            ]);

            array_push($tableTemp, [
                'keterangan'    => 'Total Pajak Pembayaran',
                'nominal'       => $penjualans['pajak_pembayaran'],
                'sum'           => $penjualans['pajak_pembayaran'],
                'type'          => 'pendapatan',
            ]);

        }

        if( isset($accountSaldo['debet']) ){
            foreach($accountSaldo['debet'] as $debet){
                array_push($tableTemp, [
                    'keterangan'    => $debet['nama_akun'],
                    'nominal'       => $debet['total'],
                    'sum'           => $debet['total'],
                    'type'          => 'pendapatan',
                ]);
            }
        }

        // Biaya
        if( count($penjualans) ){
            array_push($tableTemp, [
                'keterangan'    => 'HPP',
                'nominal'       => $penjualans['total_hpp'],
                'sum'           => -abs($penjualans['total_hpp']),
                'type'          => 'biaya',
            ]);

            array_push($tableTemp, [
                'keterangan'    => 'Diskon',
                'nominal'       => $penjualans['diskon'],
                'sum'           => -abs($penjualans['diskon']),
                'type'          => 'biaya',
            ]);
        }

        if( isset($accountSaldo['kredit']) ){
            foreach($accountSaldo['kredit'] as $kredit){
                array_push($tableTemp, [
                    'keterangan'    => $kredit['nama_akun'],
                    'nominal'       => $kredit['total'],
                    'sum'           => -abs($kredit['total']),
                    'type'          => 'biaya',
                ]);
            }
        }

        return $tableTemp;
    }
}
