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
use Gate;

class ReportController extends Controller
{
    /* Pertanggal */
    public function index(Request $request) //pertanggal
    {
        return redirect('/report/pertanggal');
    }

    public function pertanggal(Request $request)
    {
        if( Gate::denies('report.pertanggal.penjualan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_pertanggal($request);

        return view(config('app.template').'.report.pertanggal', $data);
    }

    public function pertanggalPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.penjualan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_pertanggal($request);

        $print = new \App\Libraries\Penjualan([
            'header' => 'Laporan Penjualan '.$data['tanggal']->format('d M Y'),
            'data' => $data['reports'],
        ]);

        $print->WritePage();
    }

    protected function _pertanggal(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $reports    = Order::ReportByDate($tanggal);
        $reports    = ConvertRawQueryToArray($reports);

        return [
            'tanggal'   => Carbon::parse($tanggal),
            'reports'   => $reports,
        ];
    }

    public function detail($id)
    {
        if( Gate::denies('report.pertanggal.penjualan.detail') ){
            return view(config('app.template').'.error.403');
        }

        $order = Order::find($id);

        if( $order->state == 'Closed' ){
            $order->load('tax', 'bayarBank', 'bayar', 'place.place.kategori');

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
        if( Gate::denies('report.pertanggal.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItem($request);

        return view(config('app.template').'.report.pertanggal-solditem', $data);
    }

    public function soldItemPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItem($request);

        $print = new \App\Libraries\SoldItem([
            'header' => 'Laporan Sold Item '.$data['tanggal']->format('d M Y'),
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _soldItem(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            order_details.`hpp`,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE orders.`tanggal` = '$tanggal'
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->where('produks.active', 1)
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

        return [
            'tanggal'   => Carbon::parse($tanggal),
            'produks'   => $produk
        ];
    }

    public function soldItemBahan(Request $request)
    {
        if( Gate::denies('report.pertanggal.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahan($request);

        return view(config('app.template').'.report.pertanggal-solditembahan', $data);
    }

    public function soldItemBahanPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahan($request);

        $print = new \App\Libraries\SoldBahan([
            'header' => 'Laporan Sold Bahan '.$data['tanggal']->format('d M Y'),
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemBahan(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $bahans = \App\Bahan::soldItem("orders.`tanggal` = '$tanggal' AND");
        return [
            'tanggal'   => Carbon::parse($tanggal),
            'bahans'   => $bahans
        ];
    }

    public function purchasedItem(Request $request)
    {
        if( Gate::denies('report.pertanggal.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItem($request);

        return view(config('app.template').'.report.pertanggal-purchaseditem', $data);
    }

    public function purchasedItemPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItem($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Produk '.$data['tanggal']->format('d M Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItem(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $produks = \App\PembelianDetail::join('produks', 'pembelian_details.relation_id', '=', 'produks.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->where('pembelians.tanggal', $tanggal)
            ->where('type', 'produk')
            ->select([
                'produks.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('produks.id')->get();

        return [
            'tanggal' => Carbon::parse($tanggal),
            'produks' => $produks,
        ];
    }

    public function purchasedItemBahan(Request $request)
    {
        if( Gate::denies('report.pertanggal.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahan($request);

        return view(config('app.template').'.report.pertanggal-purchaseditembahan', $data);
    }

    public function purchasedItemBahanPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahan($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Bahan '.$data['tanggal']->format('d M Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemBahan(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $bahans = \App\PembelianDetail::join('bahans', 'pembelian_details.relation_id', '=', 'bahans.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->where('pembelians.tanggal', $tanggal)
            ->where('type', 'bahan')
            ->select([
                'bahans.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('bahans.id')->get();

        return [
            'tanggal' => Carbon::parse($tanggal),
            'bahans' => $bahans,
        ];
    }

    public function adjustment(Request $request)
    {
        if( Gate::denies('report.pertanggal.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustment($request);

        return view(config('app.template').'.report.pertanggal-adjustments', $data);
    }

    public function adjustmentPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustment($request);

        $print = new \App\Libraries\Adjustment([
            'header' => 'Laporan Adjustment '.$data['tanggal']->format('d M Y'),
            'data' => $data['adjustments'],
        ]);

        $print->WritePage();
    }

    protected function _adjustment(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        // Produk
        # Increase
        $produk_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->where('adjustments.tanggal', $tanggal)
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();
        # Reduction
        $produk_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->where('adjustments.tanggal', $tanggal)
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();

        // Bahan
        # Increase
        $bahan_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->where('adjustments.tanggal', $tanggal)
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();
        # Reduction
        $bahan_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->where('adjustments.tanggal', $tanggal)
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();

        return [
            'tanggal' => Carbon::parse($tanggal),
            'adjustments' => [
                'increase' => array_merge($produk_increase->toArray(), $bahan_increase->toArray()),
                'reduction' => array_merge($produk_reduction->toArray(), $bahan_reduction->toArray()),
            ],
        ];
    }

    public function stok(Request $request)
    {
        if( Gate::denies('report.pertanggal.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stok($request);

        return view(config('app.template').'.report.pertanggal-stok', $data);
    }

    public function stokPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stok($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Produk '.$data['tanggal']->format('d M Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _stok(Request $request) // produk
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $produks = \App\Produk::MutasiStok($tanggal);

        return [
            'tanggal' => Carbon::parse($tanggal),
            'produks' => $produks,
        ];
    }

    public function stokBahan(Request $request) // bahan
    {
        if( Gate::denies('report.pertanggal.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahan($request);

        return view(config('app.template').'.report.pertanggal-stokbahan', $data);
    }

    public function stokBahanPrint(Request $request) // bahan
    {
        if( Gate::denies('report.pertanggal.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahan($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Bahan '.$data['tanggal']->format('d M Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _stokBahan(Request $request) // bahan
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $bahans = \App\Bahan::MutasiStok($tanggal);

        return [
            'tanggal' => Carbon::parse($tanggal),
            'bahans' => $bahans,
        ];
    }

    public function karyawan(Request $request)
    {
        if( Gate::denies('report.pertanggal.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawan($request);

        return view(config('app.template').'.report.pertanggal-karyawan', $data);
    }

    public function karyawanPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawan($request);

        $print = new \App\Libraries\Karyawan([
            'header' => 'Laporan Penjualan Karyawan '.$data['tanggal']->format('d M Y'),
            'data' => $data['karyawans'],
        ]);

        $print->WritePage();
    }

    protected function _karyawan(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where('orders.tanggal', $tanggal)
            ->where('state', 'Closed')
            ->where('karyawans.active', 1)
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        return [
            'tanggal'   => Carbon::parse($tanggal),
            'karyawans' => $karyawans,
        ];
    }

    public function karyawanDetail(Request $request)
    {
        if( Gate::denies('report.pertanggal.karyawan.detail') ){
            return view(config('app.template').'.error.403');
        }

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
                return view(config('app.template').'.error.404');
            }
        }else{
            return view(config('app.template').'.error.404');
        }
    }

    public function customer(Request $request)
    {
        if( Gate::denies('report.pertanggal.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customer($request);

        return view(config('app.template').'.report.pertanggal-customer', $data);
    }

    public function customerPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customer($request);

        $print = new \App\Libraries\Customer([
            'header' => 'Laporan Customer / Member '.$data['tanggal']->format('d M Y'),
            'data' => $data['customers'],
        ]);

        $print->WritePage();
    }

    protected function _customer(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $customers = \App\Customer::Report($tanggal);

        return [
            'tanggal'   => Carbon::parse($tanggal),
            'customers' => $customers,
        ];
    }

    public function labaRugi(Request $request)
    {
        if( Gate::denies('report.pertanggal.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugi($request);

        return view(config('app.template').'.report.pertanggal-labarugi', $data);
    }

    public function labaRugiPrint(Request $request)
    {
        if( Gate::denies('report.pertanggal.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugi($request);

        $print = new \App\Libraries\LabaRugi([
            'header' => 'Laporan Laba/Rugi '.$data['tanggal']->format('d M Y'),
            'data' => $data['tableTemp'],
        ]);

        $print->WritePage();
    }

    protected function _labaRugi(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $penjualans = Order::ReportGroup("orders.`tanggal` = '$tanggal'", "GROUP BY tanggal");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->leftJoin(DB::raw("(SELECT accounts.`id` AS account_id, accounts.`nama_akun`, reports.display
                    FROM accounts
                    INNER JOIN account_report ON accounts.`id` = account_report.`account_id`
                    INNER JOIN reports ON account_report.`report_id` = reports.id
                    WHERE reports.key = 'labarugi')temp_report"),
                function($join){
                    $join->on('accounts.id', '=', 'temp_report.account_id');
                }
            )
            ->where('account_saldos.tanggal', $tanggal)
            ->whereNull('account_saldos.relation_id')
            ->whereNotNull('temp_report.account_id')
            ->groupBy('accounts.id')
            ->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
            ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        return ['tanggal' => Carbon::createFromFormat('Y-m-d', $tanggal), 'tableTemp' => $tableTemp];
    }
    /* End Pertanggal */

    /* Periode */
    public function soldItemPeriode(Request $request)
    {
        if( Gate::denies('report.periode.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemPeriode($request);

        return view(config('app.template').'.report.periode-solditem', $data);
    }

    public function soldItemPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemPeriode($request);

        $print = new \App\Libraries\SoldItem([
            'header' => 'Laporan Sold Item '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            order_details.`hpp`,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE (orders.`tanggal` BETWEEN '$tanggal' AND '$to_tanggal' )
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->where('produks.active', 1)
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

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'produks'   => $produk
        ];
    }

    public function soldItemBahanPeriode(Request $request)
    {
        if( Gate::denies('report.periode.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahanPeriode($request);

        return view(config('app.template').'.report.periode-solditembahan', $data);
    }

    public function soldItemBahanPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahanPeriode($request);

        $print = new \App\Libraries\SoldBahan([
            'header' => 'Laporan Sold Bahan '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemBahanPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $bahans = \App\Bahan::soldItem("( orders.`tanggal` BETWEEN '$tanggal' AND '$to_tanggal' ) AND");
        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'bahans'   => $bahans
        ];
    }

    public function purchasedItemPeriode(Request $request)
    {
        if( Gate::denies('report.periode.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemPeriode($request);

        return view(config('app.template').'.report.periode-purchaseditem', $data);
    }

    public function purchasedItemPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemPeriode($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Produk '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemPeriode(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $produks = \App\PembelianDetail::join('produks', 'pembelian_details.relation_id', '=', 'produks.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->whereBetween('pembelians.tanggal', [$tanggal, $to_tanggal])
            ->where('type', 'produk')
            ->select([
                'produks.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('produks.id')->get();

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'produks'   => $produks
        ];
    }

    public function purchasedItemBahanPeriode(Request $request)
    {
        if( Gate::denies('report.periode.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahanPeriode($request);

        return view(config('app.template').'.report.periode-purchaseditembahan', $data);
    }

    public function purchasedItemBahanPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahanPeriode($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Bahan '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemBahanPeriode(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $bahans = \App\PembelianDetail::join('bahans', 'pembelian_details.relation_id', '=', 'bahans.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->whereBetween('pembelians.tanggal', [$tanggal, $to_tanggal])
            ->where('type', 'bahan')
            ->select([
                'bahans.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('bahans.id')->get();

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'bahans'   => $bahans
        ];
    }

    public function adjustmentPeriode(Request $request)
    {
        if( Gate::denies('report.periode.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustmentPeriode($request);

        return view(config('app.template').'.report.periode-adjustments', $data);
    }

    public function adjustmentPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustmentPeriode($request);

        $print = new \App\Libraries\Adjustment([
            'header' => 'Laporan Adjustment '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'data' => $data['adjustments'],
        ]);

        $print->WritePage();
    }

    protected function _adjustmentPeriode(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        // Produk
        # Increase
        $produk_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->whereBetween('adjustments.tanggal', [$tanggal, $to_tanggal])
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();
        # Reduction
        $produk_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->whereBetween('adjustments.tanggal', [$tanggal, $to_tanggal])
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();

        // Bahan
        # Increase
        $bahan_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->whereBetween('adjustments.tanggal', [$tanggal, $to_tanggal])
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();
        # Reduction
        $bahan_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->whereBetween('adjustments.tanggal', [$tanggal, $to_tanggal])
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'adjustments' => [
                'increase' => array_merge($produk_increase->toArray(), $bahan_increase->toArray()),
                'reduction' => array_merge($produk_reduction->toArray(), $bahan_reduction->toArray()),
            ],
        ];
    }

    public function stokPeriode(Request $request)
    {
        if( Gate::denies('report.periode.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokPeriode($request);

        return view(config('app.template').'.report.periode-stok', $data);
    }

    public function stokPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokPeriode($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Produk '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _stokPeriode(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $produks = \App\Produk::MutasiStok($tanggal, $to_tanggal);

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'produks' => $produks,
        ];
    }

    public function stokBahanPeriode(Request $request)
    {
        if( Gate::denies('report.periode.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahanPeriode($request);

        return view(config('app.template').'.report.periode-stokbahan', $data);
    }

    public function stokBahanPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahanPeriode($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Bahan '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _stokBahanPeriode(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $bahans = \App\Bahan::MutasiStok($tanggal, $to_tanggal);

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'bahans' => $bahans,
        ];
    }

    public function karyawanPeriode(Request $request)
    {
        if( Gate::denies('report.periode.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawanPeriode($request);

        return view(config('app.template').'.report.periode-karyawan', $data);
    }

    public function karyawanPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawanPeriode($request);

        $print = new \App\Libraries\Karyawan([
            'header' => 'Laporan Penjualan Karyawan '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'data' => $data['karyawans'],
        ]);

        $print->WritePage();
    }

    protected function _karyawanPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->whereBetween('orders.tanggal', [$tanggal, $to_tanggal])
            ->where('state', 'Closed')
            ->where('karyawans.active', 1)
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'karyawans' => $karyawans,
        ];
    }

    public function customerPeriode(Request $request)
    {
        if( Gate::denies('report.periode.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customerPeriode($request);

        return view(config('app.template').'.report.periode-customer', $data);
    }

    public function customerPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customerPeriode($request);

        $print = new \App\Libraries\Customer([
            'header' => 'Laporan Customer / Member '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'data' => $data['customers'],
        ]);

        $print->WritePage();
    }

    protected function _customerPeriode(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $customers = \App\Customer::Report($tanggal, $to_tanggal);

        return [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'=> Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'customers' => $customers,
        ];
    }

    public function labaRugiPeriode(Request $request)
    {
        if( Gate::denies('report.periode.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugiPeriode($request);

        return view(config('app.template').'.report.periode-labarugi', $data);
    }

    public function labaRugiPeriodePrint(Request $request)
    {
        if( Gate::denies('report.periode.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugiPeriode($request);

        $print = new \App\Libraries\LabaRugi([
            'header' => 'Laporan Laba/Rugi '.$data['tanggal']->format('d M Y').' s/d '.$data['to_tanggal']->format('d M Y'),
            'data' => $data['tableTemp'],
        ]);

        $print->WritePage();
    }

    protected function _labaRugiPeriode(Request $request)
    {
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : $tanggal;

        $penjualans = Order::ReportGroup("(orders.`tanggal` BETWEEN '$tanggal' AND '$to_tanggal')", "");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->leftJoin(DB::raw("(SELECT accounts.`id` AS account_id, accounts.`nama_akun`, reports.display
                    FROM accounts
                    INNER JOIN account_report ON accounts.`id` = account_report.`account_id`
                    INNER JOIN reports ON account_report.`report_id` = reports.id
                    WHERE reports.key = 'labarugi')temp_report"),
                function($join){
                    $join->on('accounts.id', '=', 'temp_report.account_id');
                }
            )
            ->whereBetween('account_saldos.tanggal', [$tanggal, $to_tanggal])
            ->whereNull('account_saldos.relation_id')
            ->whereNotNull('temp_report.account_id')
            ->groupBy('accounts.id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        return [
            'tanggal'       => Carbon::createFromFormat('Y-m-d', $tanggal),
            'to_tanggal'    => Carbon::createFromFormat('Y-m-d', $to_tanggal),
            'tableTemp'     => $tableTemp
        ];
    }
    /* End Periode */

    /* Perbulan */
    public function perbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.penjualan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_perbulan($request);

        return view(config('app.template').'.report.perbulan', $data);
    }

    public function perbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.penjualan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_perbulan($request);

        $print = new \App\Libraries\PenjualanGroup([
            'header'    => 'Laporan Penjualan Bulan '.$data['tanggal']->format('M Y'),
            'data'      => $data['reports'],
            'dates'     => $data['dates'],
            'first_column' => 'Tanggal',
            'format_first_column' => 'd M Y',
            'search_column' => 'tanggal',
            'format_search' => 'Y-m-d',
        ]);

        $print->WritePage();
    }

    protected function _perbulan(Request $request)
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

        return [
            'tanggal'   => Carbon::parse($bulan),
            'dates'     => $dates,
            'reports'   => $reports,
        ];
    }

    public function soldItemPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemPerbulan($request);

        return view(config('app.template').'.report.perbulan-solditem', $data);
    }

    public function soldItemPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemPerbulan($request);

        $print = new \App\Libraries\SoldItem([
            'header' => 'Laporan Sold Item Bulan '.$data['tanggal']->format('M Y'),
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            order_details.`hpp`,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan'
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->where('produks.active', 1)
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

        return [
            'tanggal'   => Carbon::parse($bulan),
            'produks'   => $produk
        ];
    }

    public function soldItemBahanPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahanPerbulan($request);

        return view(config('app.template').'.report.perbulan-solditembahan', $data);
    }

    public function soldItemBahanPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahanPerbulan($request);

        $print = new \App\Libraries\SoldBahan([
            'header' => 'Laporan Sold Bahan Bulan '.$data['tanggal']->format('M Y'),
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemBahanPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $bahans = \App\Bahan::soldItem("SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan' AND");

        return [
            'tanggal'   => Carbon::parse($bulan),
            'bahans'   => $bahans
        ];
    }

    public function purchasedItemPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemPerbulan($request);

        return view(config('app.template').'.report.perbulan-purchaseditem', $data);
    }

    public function purchasedItemPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemPerbulan($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Produk Bulan '.$data['tanggal']->format('M Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $produks = \App\PembelianDetail::join('produks', 'pembelian_details.relation_id', '=', 'produks.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->where(DB::raw('SUBSTRING(pembelians.tanggal, 1, 7)'), $bulan)
            ->where('type', 'produk')
            ->select([
                'produks.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('produks.id')->get();

        return [
            'tanggal' => Carbon::parse($bulan),
            'produks' => $produks
        ];
    }

    public function purchasedItemBahanPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahanPerbulan($request);

        return view(config('app.template').'.report.perbulan-purchaseditembahan', $data);
    }

    public function purchasedItemBahanPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahanPerbulan($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Bahan Bulan '.$data['tanggal']->format('M Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemBahanPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $bahans = \App\PembelianDetail::join('bahans', 'pembelian_details.relation_id', '=', 'bahans.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->where(DB::raw('SUBSTRING(pembelians.tanggal, 1, 7)'), $bulan)
            ->where('type', 'bahan')
            ->select([
                'bahans.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('bahans.id')->get();

        return [
            'tanggal' => Carbon::parse($bulan),
            'bahans' => $bahans
        ];
    }

    public function adjustmentPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustmentPerbulan($request);

        return view(config('app.template').'.report.perbulan-adjustments', $data);
    }

    public function adjustmentPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustmentPerbulan($request);

        $print = new \App\Libraries\Adjustment([
            'header' => 'Laporan Adjustment Bulan '.$data['tanggal']->format('M Y'),
            'data' => $data['adjustments'],
        ]);

        $print->WritePage();
    }

    protected function _adjustmentPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        // Produk
        # Increase
        $produk_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 7)'), $bulan)
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();
        # Reduction
        $produk_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 7)'), $bulan)
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();

        // Bahan
        # Increase
        $bahan_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 7)'), $bulan)
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();
        # Reduction
        $bahan_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 7)'), $bulan)
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();

        return [
            'tanggal' => Carbon::parse($bulan),
            'adjustments' => [
                'increase' => array_merge($produk_increase->toArray(), $bahan_increase->toArray()),
                'reduction' => array_merge($produk_reduction->toArray(), $bahan_reduction->toArray()),
            ],
        ];
    }

    public function stokPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokPerbulan($request);

        return view(config('app.template').'.report.perbulan-stok', $data);
    }

    public function stokPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokPerbulan($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Produk Bulan '.$data['tanggal']->format('M Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _stokPerbulan(Request $request)
    {
        $bulan  = $request->get('bulan') ? $request->get('bulan') : date('Y-m');
        $start  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $end    = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $produks = \App\Produk::MutasiStok($start->format('Y-m-d'), $end->format('Y-m-d'));

        return [
            'tanggal' => Carbon::parse($bulan),
            'produks' => $produks,
        ];
    }

    public function stokBahanPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahanPerbulan($request);

        return view(config('app.template').'.report.perbulan-stokbahan', $data);
    }

    public function stokBahanPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahanPerbulan($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Bahan Bulan '.$data['tanggal']->format('M Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _stokBahanPerbulan(Request $request)
    {
        $bulan  = $request->get('bulan') ? $request->get('bulan') : date('Y-m');
        $start  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $end    = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $bahans = \App\Bahan::MutasiStok($start->format('Y-m-d'), $end->format('Y-m-d'));

        return [
            'tanggal' => Carbon::parse($bulan),
            'bahans' => $bahans,
        ];
    }

    public function karyawanPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawanPerbulan($request);

        return view(config('app.template').'.report.perbulan-karyawan', $data);
    }

    public function karyawanPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawanPerbulan($request);

        $print = new \App\Libraries\Karyawan([
            'header' => 'Laporan Penjualan Karyawan Bulan '.$data['tanggal']->format('M Y'),
            'data' => $data['karyawans'],
        ]);

        $print->WritePage();
    }

    protected function _karyawanPerbulan(Request $request)
    {
        $bulan      = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where(DB::raw('SUBSTRING(orders.tanggal, 1, 7)'), $bulan)
            ->where('state', 'Closed')
            ->where('karyawans.active', 1)
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        return [
            'tanggal'   => Carbon::parse($bulan),
            'karyawans' => $karyawans,
        ];
    }

    public function customerPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customerPerbulan($request);

        return view(config('app.template').'.report.perbulan-customer', $data);
    }

    public function customerPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customerPerbulan($request);

        $print = new \App\Libraries\Customer([
            'header' => 'Laporan Customer / Member Bulan '.$data['tanggal']->format('M Y'),
            'data' => $data['customers'],
        ]);

        $print->WritePage();
    }

    protected function _customerPerbulan(Request $request)
    {
        $bulan  = $request->get('bulan') ? $request->get('bulan') : date('Y-m');
        $start  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $end    = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $customers = \App\Customer::Report($start->format('Y-m-d'), $end->format('Y-m-d'));

        return [
            'tanggal'   => Carbon::parse($bulan),
            'customers' => $customers,
        ];
    }

    public function labaRugiPerbulan(Request $request)
    {
        if( Gate::denies('report.perbulan.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugiPerbulan($request);

        return view(config('app.template').'.report.perbulan-labarugi', $data);
    }

    public function labaRugiPerbulanPrint(Request $request)
    {
        if( Gate::denies('report.perbulan.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugiPerbulan($request);

        $print = new \App\Libraries\LabaRugi([
            'header' => 'Laporan Laba/Rugi Bulan '.$data['tanggal']->format('M Y'),
            'data' => $data['tableTemp'],
        ]);

        $print->WritePage();
    }

    protected function _labaRugiPerbulan(Request $request)
    {
        $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');

        $penjualans = Order::ReportGroup("SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan'", "GROUP BY SUBSTRING(tanggal, 1, 7)");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->leftJoin(DB::raw("(SELECT accounts.`id` AS account_id, accounts.`nama_akun`, reports.display
                    FROM accounts
                    INNER JOIN account_report ON accounts.`id` = account_report.`account_id`
                    INNER JOIN reports ON account_report.`report_id` = reports.id
                    WHERE reports.key = 'labarugi')temp_report"),
                function($join){
                    $join->on('accounts.id', '=', 'temp_report.account_id');
                }
            )
            ->where(DB::raw('SUBSTRING(account_saldos.tanggal, 1, 7)'), $bulan)
            ->whereNull('account_saldos.relation_id')
            ->whereNotNull('temp_report.account_id')
            ->groupBy('accounts.id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        return ['tanggal' => Carbon::createFromFormat('Y-m', $bulan), 'tableTemp' => $tableTemp];
    }
    /* End Perbulan */

    /* Pertahun */
    public function pertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.penjualan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_pertahun($request);

        return view(config('app.template').'.report.pertahun', $data);
    }

    public function pertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.penjualan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_pertahun($request);

        $print = new \App\Libraries\PenjualanGroup([
            'header'    => 'Laporan Penjualan Tahun '.$data['tanggal']->format('Y'),
            'data'      => $data['reports'],
            'dates'     => $data['months'],
            'first_column' => 'Bulan',
            'format_first_column' => 'M Y',
            'search_column' => 'bulan',
            'format_search' => 'Y-m',
        ]);

        $print->WritePage();
    }

    protected function _pertahun(Request $request)
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

        return [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'months'    => $months,
            'reports'   => $reports,
        ];
    }

    public function soldItemPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemPertahun($request);

        return view(config('app.template').'.report.pertahun-solditem', $data);
    }

    public function soldItemPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemPertahun($request);

        $print = new \App\Libraries\SoldItem([
            'header' => 'Laporan Sold Item Tahun '.$data['tanggal']->format('Y'),
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $produk = Produk::leftJoin(DB::raw("(SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            order_details.`hpp`,
            order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            order_details.`use_mark_up`, order_details.`mark_up`
            FROM order_details
            LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            INNER JOIN orders ON order_details.`order_id` = orders.`id`
            WHERE SUBSTRING(orders.`tanggal`, 1, 4) = '$tahun'
            AND orders.`state` = 'Closed'
            GROUP BY order_details.`id`)temp_order_produks"), function( $join ){
                    $join->on('produks.id', '=', 'temp_order_produks.produk_id');
                }
            )
            ->where('produks.active', 1)
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

        return [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'produks'   => $produk
        ];
    }

    public function soldItemBahanPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahanPertahun($request);

        return view(config('app.template').'.report.pertahun-solditembahan', $data);
    }

    public function soldItemBahanPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.solditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_soldItemBahanPertahun($request);

        $print = new \App\Libraries\SoldBahan([
            'header' => 'Laporan Sold Bahan Tahun '.$data['tanggal']->format('Y'),
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _soldItemBahanPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $bahans = \App\Bahan::soldItem("SUBSTRING(orders.`tanggal`, 1, 4) = '$tahun' AND");

        return [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'bahans'   => $bahans
        ];
    }

    public function purchasedItemPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemPertahun($request);

        return view(config('app.template').'.report.pertahun-purchaseditem', $data);
    }

    public function purchasedItemPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemPertahun($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Produk Tahun '.$data['tanggal']->format('Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $produks = \App\PembelianDetail::join('produks', 'pembelian_details.relation_id', '=', 'produks.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->where(DB::raw('SUBSTRING(pembelians.tanggal, 1, 4)'), $tahun)
            ->where('type', 'produk')
            ->select([
                'produks.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('produks.id')->get();

        return [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'produks'   => $produks
        ];
    }

    public function purchasedItemBahanPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahanPertahun($request);

        return view(config('app.template').'.report.pertahun-purchaseditembahan', $data);
    }

    public function purchasedItemBahanPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.purchaseditem') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_purchasedItemBahanPertahun($request);

        $print = new \App\Libraries\Purchasing([
            'header' => 'Laporan Pembelian Bahan Tahun '.$data['tanggal']->format('Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _purchasedItemBahanPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $bahans = \App\PembelianDetail::join('bahans', 'pembelian_details.relation_id', '=', 'bahans.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->where(DB::raw('SUBSTRING(pembelians.tanggal, 1, 4)'), $tahun)
            ->where('type', 'bahan')
            ->select([
                'bahans.nama',
                DB::raw('SUM(pembelian_details.qty)qty'),
                'pembelian_details.satuan',
                DB::raw('ROUND(SUM(pembelian_details.harga)/SUM(pembelian_details.stok))harga_per_stok'),
                DB::raw('SUM(pembelian_details.stok)stok'),
                DB::raw('SUM(pembelian_details.harga)total'),
            ])->groupBy('bahans.id')->get();

        return [
            'tanggal'  => Carbon::createFromFormat('Y', $tahun),
            'bahans'   => $bahans
        ];
    }

    public function adjustmentPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustmentPertahun($request);

        return view(config('app.template').'.report.pertahun-adjustments', $data);
    }

    public function adjustmentPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.adjustment') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_adjustmentPertahun($request);

        $print = new \App\Libraries\Adjustment([
            'header' => 'Laporan Adjustment Tahun '.$data['tanggal']->format('Y'),
            'data' => $data['adjustments'],
        ]);

        $print->WritePage();
    }

    protected function _adjustmentPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        // Produk
        # Increase
        $produk_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 4)'), $tahun)
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();
        # Reduction
        $produk_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 4)'), $tahun)
            ->where('adjustment_details.type', 'produk')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('produks.id')
            ->select([
                'produks.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'produks.satuan',
            ])
            ->get();

        // Bahan
        # Increase
        $bahan_increase = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 4)'), $tahun)
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'increase')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();
        # Reduction
        $bahan_reduction = \App\AdjustmentDetail::join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->where(DB::raw('SUBSTRING(adjustments.tanggal, 1, 4)'), $tahun)
            ->where('adjustment_details.type', 'bahan')
            ->where('adjustment_details.state', 'reduction')
            ->groupBy('bahans.id')
            ->select([
                'bahans.nama',
                'adjustment_details.type',
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty)/SUM(adjustment_details.qty))harga_avg'),
                DB::raw('SUM(adjustment_details.qty)qty_stok'),
                DB::raw('(SUM(adjustment_details.harga*adjustment_details.qty))subtotal'),
                'bahans.satuan',
            ])
            ->get();

        return [
            'tanggal'  => Carbon::createFromFormat('Y', $tahun),
            'adjustments' => [
                'increase' => array_merge($produk_increase->toArray(), $bahan_increase->toArray()),
                'reduction' => array_merge($produk_reduction->toArray(), $bahan_reduction->toArray()),
            ],
        ];
    }

    public function stokPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokPertahun($request);

        return view(config('app.template').'.report.pertahun-stok', $data);
    }

    public function stokPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokPertahun($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Produk Tahun '.$data['tanggal']->format('Y'),
            'type'  => 'Produk',
            'data' => $data['produks'],
        ]);

        $print->WritePage();
    }

    protected function _stokPertahun(Request $request)
    {
        $tahun  = $request->get('tahun') ? $request->get('tahun') : date('Y');
        $start  = Carbon::parse('first day of January '.$tahun);
        $end    = Carbon::parse('last day of December '.$tahun);

        $produks = \App\Produk::MutasiStok($start->format('Y-m-d'), $end->format('Y-m-d'));

        return [
            'tanggal' => Carbon::createFromFormat('Y', $tahun),
            'produks' => $produks,
        ];
    }

    public function stokBahanPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahanPertahun($request);

        return view(config('app.template').'.report.pertahun-stokbahan', $data);
    }

    public function stokBahanPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.stok') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_stokBahanPertahun($request);

        $print = new \App\Libraries\MutasiStok([
            'header' => 'Laporan Mutasi Stok Bahan Tahun '.$data['tanggal']->format('Y'),
            'type'  => 'Bahan',
            'data' => $data['bahans'],
        ]);

        $print->WritePage();
    }

    protected function _stokBahanPertahun(Request $request)
    {
        $tahun  = $request->get('tahun') ? $request->get('tahun') : date('Y');
        $start  = Carbon::parse('first day of January '.$tahun);
        $end    = Carbon::parse('last day of December '.$tahun);

        $bahans = \App\Bahan::MutasiStok($start->format('Y-m-d'), $end->format('Y-m-d'));

        return [
            'tanggal' => Carbon::createFromFormat('Y', $tahun),
            'bahans' => $bahans,
        ];
    }

    public function karyawanPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawanPertahun($request);

        return view(config('app.template').'.report.pertahun-karyawan', $data);
    }

    public function karyawanPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.karyawan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_karyawanPertahun($request);

        $print = new \App\Libraries\Karyawan([
            'header' => 'Laporan Penjualan Karyawan Tahun '.$data['tanggal']->format('Y'),
            'data' => $data['karyawans'],
        ]);

        $print->WritePage();
    }

    protected function _karyawanPertahun(Request $request)
    {
        $tahun      = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $karyawans  = \App\Karyawan::join('orders', 'karyawans.id', '=', 'orders.karyawan_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->where(DB::raw('SUBSTRING(orders.tanggal, 1, 4)'), $tahun)
            ->where('state', 'Closed')
            ->where('karyawans.active', 1)
            ->select(['karyawans.id', 'karyawans.nama',
                DB::raw('SUM( order_details.`harga_jual` * ( order_details.`qty` - ifnull(order_detail_returns.qty, 0) ) )total_penjualan')
            ])
            ->groupBy('karyawans.id')
            ->get();

        return [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'karyawans' => $karyawans,
        ];
    }

    public function customerPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customerPertahun($request);

        return view(config('app.template').'.report.pertahun-customer', $data);
    }

    public function customerPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.customer') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_customerPertahun($request);

        $print = new \App\Libraries\Customer([
            'header' => 'Laporan Customer / Member Tahun '.$data['tanggal']->format('Y'),
            'data' => $data['customers'],
        ]);

        $print->WritePage();
    }

    protected function _customerPertahun(Request $request)
    {
        $tahun  = $request->get('tahun') ? $request->get('tahun') : date('Y');
        $start  = Carbon::parse('first day of January '.$tahun);
        $end    = Carbon::parse('last day of December '.$tahun);

        $customers = \App\Customer::Report($start->format('Y-m-d'), $end->format('Y-m-d'));

        return [
            'tanggal'   => Carbon::createFromFormat('Y', $tahun),
            'customers' => $customers,
        ];
    }

    public function labaRugiPertahun(Request $request)
    {
        if( Gate::denies('report.pertahun.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugiPertahun($request);

        return view(config('app.template').'.report.pertahun-labarugi', $data);
    }

    public function labaRugiPertahunPrint(Request $request)
    {
        if( Gate::denies('report.pertahun.labarugi') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_labaRugiPertahun($request);

        $print = new \App\Libraries\LabaRugi([
            'header' => 'Laporan Laba/Rugi Tahun '.$data['tanggal']->format('Y'),
            'data' => $data['tableTemp'],
        ]);

        $print->WritePage();
    }

    protected function _labaRugiPertahun(Request $request)
    {
        $tahun = $request->get('tahun') ? $request->get('tahun') : date('Y');

        $penjualans = Order::ReportGroup("SUBSTRING(orders.`tanggal`, 1, 4) = '$tahun'", "GROUP BY SUBSTRING(tanggal, 1, 4)");
        $penjualans = ConvertRawQueryToArray($penjualans);

        $accountSaldo = \App\AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->leftJoin(DB::raw("(SELECT accounts.`id` AS account_id, accounts.`nama_akun`, reports.display
                    FROM accounts
                    INNER JOIN account_report ON accounts.`id` = account_report.`account_id`
                    INNER JOIN reports ON account_report.`report_id` = reports.id
                    WHERE reports.key = 'labarugi')temp_report"),
                function($join){
                    $join->on('accounts.id', '=', 'temp_report.account_id');
                }
            )
            ->where(DB::raw('SUBSTRING(account_saldos.tanggal, 1, 4)'), $tahun)
            ->whereNull('account_saldos.relation_id')
            ->whereNotNull('temp_report.account_id')
            ->groupBy('accounts.id')->select([
                'accounts.nama_akun', DB::raw('SUM(account_saldos.nominal)total'), 'account_saldos.type'
                ])->get()->groupBy('type');

        $tableTemp = $this->buildLabaRugiTable(['penjualans' => $penjualans, 'account_saldo' => $accountSaldo]);

        return ['tanggal' => Carbon::createFromFormat('Y', $tahun), 'tableTemp' => $tableTemp];
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
                'keterangan'    => 'Total Service',
                'nominal'       => $penjualans['total_service'],
                'sum'           => $penjualans['total_service'],
                'type'          => 'pendapatan',
            ]);

            array_push($tableTemp, [
                'keterangan'    => 'Total Pajak',
                'nominal'       => $penjualans['pajak'],
                'sum'           => $penjualans['pajak'],
                'type'          => 'pendapatan',
            ]);

            /*array_push($tableTemp, [
                'keterangan'    => 'Total Pajak Pembayaran',
                'nominal'       => $penjualans['pajak_pembayaran'],
                'sum'           => $penjualans['pajak_pembayaran'],
                'type'          => 'pendapatan',
            ]);*/

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
