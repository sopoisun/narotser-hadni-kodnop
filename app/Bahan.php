<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StokBahan;
use Carbon\Carbon;
use DB;

class Bahan extends Model
{
    protected $fillable = ['nama', 'satuan', 'satuan_beli', 'harga', 'qty_warning', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function produkDetail()
    {
        return $this->hasOne('App\ProdukDetail', 'bahan_id', 'id');
    }

    public function pembelianDetail()
    {
        return $this->hasOne('App\PembelianDetail', 'relation_id', 'id');
    }

    public function adjustmentDetail()
    {
        return $this->hasOne('App\AdjustmentDetail', 'relation_id', 'id');
    }

    public function orderDetailBahan()
    {
        return $this->hasOne('App\OrderDetailBahan', 'bahan_id', 'id');
    }

    public function stokBahan()
    {
        return $this->hasOne(StokBahan::class, 'bahan_id', 'id');
    }

    public static function stok()
    {
        return self::leftJoin(DB::raw("(SELECT pembelian_details.`relation_id`, SUM(pembelian_details.`stok`)stok
                    FROM pembelian_details WHERE pembelian_details.`type` = 'bahan'
                    GROUP BY pembelian_details.`relation_id`)temp_pembelian"),
                    function($join){
                        $join->on('bahans.id', '=', 'temp_pembelian.relation_id');
                    }
            )
            //->leftJoin('pembelian_details', 'bahans.id', '=', 'pembelian_details.relation_id')
            ->leftJoin(DB::raw("(SELECT adjustment_details.`relation_id`, SUM(adjustment_details.`qty`)AS qty
                    FROM adjustment_details WHERE adjustment_details.`state`= 'increase'
                    AND adjustment_details.`type` = 'bahan'
                    GROUP BY adjustment_details.`relation_id`)as adjustment_increase"),
                function($join){
                    $join->on('bahans.id', '=', 'adjustment_increase.relation_id');
                }
            )
            ->leftJoin(DB::raw("(SELECT adjustment_details.`relation_id`, SUM(adjustment_details.`qty`)AS qty
                    FROM adjustment_details WHERE adjustment_details.`state`= 'reduction'
                    AND adjustment_details.`type` = 'bahan'
                    GROUP BY adjustment_details.`relation_id`)as adjustment_reduction"),
                function($join){
                    $join->on('bahans.id', '=', 'adjustment_reduction.relation_id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT order_detail_bahans.`bahan_id`, SUM(order_detail_bahans.`qty`* (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0)))qty
                    FROM order_detail_bahans
                    INNER JOIN order_details ON order_detail_bahans.`order_detail_id` = order_details.`id`
                    LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                    INNER JOIN orders ON order_details.`order_id` = orders.`id`
                    WHERE orders.`state` in ('Closed', 'On Going')
                    GROUP BY order_detail_bahans.`bahan_id`) penjualan"),
                function($join){
                    $join->on('bahans.id', '=', 'penjualan.bahan_id');
                }
            )
            ->select([
                'bahans.*',
                DB::raw('ifnull(temp_pembelian.stok, 0)as stok_pembelian'),
                DB::raw('ifnull(penjualan.qty, 0)as penjualan_stok'),
                DB::raw('ifnull(adjustment_increase.qty, 0)as adjustment_increase_stok'),
                DB::raw('ifnull(adjustment_reduction.qty, 0)as adjustment_reduction_stok'),
                DB::raw('(( ifnull(temp_pembelian.stok, 0) + ifnull(adjustment_increase.qty, 0) ) - ( ifnull(penjualan.qty, 0) + ifnull(adjustment_reduction.qty, 0) ))sisa_stok'),
            ])
            ->where('bahans.active', 1)
            ->groupBy('bahans.id');
            /*->orderBy('bahans.id')
            ->get();*/
    }

    public static function soldItem($where="")
    {
        return self::leftJoin(DB::raw("(SELECT order_detail_bahans.`bahan_id`,
                    ROUND(SUM(order_detail_bahans.`harga`)/COUNT(order_detail_bahans.`bahan_id`))harga,
                    SUM(order_detail_bahans.`qty`* (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0)))qty
                    FROM order_detail_bahans
                    INNER JOIN order_details ON order_detail_bahans.`order_detail_id` = order_details.`id`
                    LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                    INNER JOIN orders ON order_details.`order_id` = orders.`id`
                    WHERE $where orders.`state` = 'Closed'
                    GROUP BY order_detail_bahans.`bahan_id`)penjualan"),
                function($join){
                    $join->on('bahans.id', '=', 'penjualan.bahan_id');
                }
            )
            ->select([
                'bahans.nama', DB::raw('ifnull(penjualan.harga, 0)harga'),
                DB::raw('ifnull(penjualan.qty, 0)terjual'),
                DB::raw('ifnull((penjualan.harga*penjualan.qty), 0)subtotal'),
            ])
            ->where('bahans.active', 1)
            ->get();
    }

    public static function MutasiStok($tanggal1, $tanggal2 = "")
    {
        $stokSebelumnya = self::AmbilStokSebelumnya($tanggal1);

        if( $tanggal2 == ""){
            $tanggal2 = $tanggal1;
        }

        $adjustmentIncrease = self::_adjustmentIncrease_Stok($tanggal1, $tanggal2);
        $adjustmentReduction = self::_adjustmentReduction_Stok($tanggal1, $tanggal2);
        $pembelian = self::_pembelian($tanggal1, $tanggal2);
        $penjualan = self::_penjualan($tanggal1, $tanggal2);

        $display = [];
        foreach ($stokSebelumnya as $produk) {
            $row = [];

            $adjustment_increase = 0;
            $idx = array_search($produk['id'], array_column($adjustmentIncrease, 'id'));
            if (false !== $idx) {
                $adjustment_increase = $adjustmentIncrease[$idx]['qty'];
            }
            $row['adjustment_increase'] = $adjustment_increase;

            $adjustment_reduction = 0;
            $idx = array_search($produk['id'], array_column($adjustmentReduction, 'id'));
            if (false !== $idx) {
                $adjustment_reduction = $adjustmentReduction[$idx]['qty'];
            }
            $row['adjustment_reduction'] = $adjustment_reduction;

            $_pembelian = 0;
            $idx = array_search($produk['id'], array_column($pembelian, 'id'));
            if (false !== $idx) {
                $_pembelian = $pembelian[$idx]['qty'];
            }
            $row['pembelian'] = $_pembelian;

            $_penjualan = 0;
            $idx = array_search($produk['id'], array_column($penjualan, 'id'));
            if (false !== $idx) {
                $_penjualan = $penjualan[$idx]['qty'];
            }
            $row['penjualan'] = $_penjualan;

            $row['sisa'] = array_sum([
                $produk['before'],
                $adjustment_increase,
                -abs($adjustment_reduction),
                $_pembelian,
                -abs($_penjualan),
            ]);

            $display[] = $produk + $row;
        }

        return $display;
    }

    protected static function AmbilStokSebelumnya($tanggal)
    {
        $bahans = self::select(['bahans.id', 'bahans.nama', 'bahans.satuan'])->get();

        $CTanggal = Carbon::createFromFormat('Y-m-d h:i:s', $tanggal.' 00:00:00');
        $CYesterday = $CTanggal->copy()->addDays(-1);
        $yesterday  = $CYesterday->format('Y-m-d');

        $adjustmentIncreaseBefore = [];
        $firstAdjustmentIncrease = \App\AdjustmentDetail::where('type', 'bahan')
            ->where('state', 'increase')->with('adjustment')->first();
        if( $firstAdjustmentIncrease ){
            if( $firstAdjustmentIncrease->adjustment->tanggal->lte($CYesterday) ){
                $adjustmentIncreaseBefore = self::_adjustmentIncrease_Stok($firstAdjustmentIncrease->adjustment->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $adjustmentReductionBefore = [];
        $firstAdjustmentReduction = \App\AdjustmentDetail::where('type', 'bahan')
            ->where('state', 'reduction')->with('adjustment')->first();
        if( $firstAdjustmentReduction ){
            if( $firstAdjustmentReduction->adjustment->tanggal->lte($CYesterday) ){
                $adjustmentReductionBefore = self::_adjustmentReduction_Stok($firstAdjustmentReduction->adjustment->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $pembelianBefore = [];
        $firstPembelian = \App\PembelianDetail::where('pembelian_details.type', 'bahan')
            ->with('pembelian')->first();
        if( $firstPembelian ){
            if( $firstPembelian->pembelian->tanggal->lte($CYesterday) ){
                $pembelianBefore = self::_pembelian($firstPembelian->pembelian->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $penjualanBefore = [];
        $firstPenjualan = \App\OrderDetailBahan::with('orderDetail.order')->first();
        if( $firstPenjualan ){
            if( $firstPenjualan->orderDetail->order->tanggal->lte($CYesterday) ){
                $penjualanBefore = self::_penjualan($firstPenjualan->orderDetail->order->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $display = [];
        foreach($bahans as $bahan){
            $temp = [];
            $sum = [];

            $idx = array_search($bahan['id'], array_column($adjustmentIncreaseBefore, 'id'));
            if (false !== $idx) {
                $sum[] = $adjustmentIncreaseBefore[$idx]['qty'];
            }

            $idx = array_search($bahan['id'], array_column($adjustmentReductionBefore, 'id'));
            if (false !== $idx) {
                $sum[] = -abs($adjustmentReductionBefore[$idx]['qty']);
            }

            $idx = array_search($bahan['id'], array_column($pembelianBefore, 'id'));
            if (false !== $idx) {
                $sum[] = $pembelianBefore[$idx]['qty'];
            }

            $idx = array_search($bahan['id'], array_column($penjualanBefore, 'id'));
            if (false !== $idx) {
                $sum[] = -abs($penjualanBefore[$idx]['qty']);
            }

            $display[] = $bahan->toArray() + ['before' => array_sum($sum)];
        }

        return $display;
    }

    protected static function _adjustmentIncrease_Stok($tanggal1, $tanggal2)
    {
        return \App\AdjustmentDetail::join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->whereBetween('adjustments.tanggal', [$tanggal1, $tanggal2])
            ->where('type', 'bahan')
            ->where('state', 'increase')
            ->groupBy('bahans.id')
            ->select([
                'bahans.id', 'bahans.nama',
                DB::raw('SUM(adjustment_details.qty)qty')
            ])->get()->toArray();
    }

    protected static function _adjustmentReduction_Stok($tanggal1, $tanggal2)
    {
        return \App\AdjustmentDetail::join('bahans', 'adjustment_details.relation_id', '=', 'bahans.id')
            ->join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->whereBetween('adjustments.tanggal', [$tanggal1, $tanggal2])
            ->where('type', 'bahan')
            ->where('state', 'reduction')
            ->groupBy('bahans.id')
            ->select([
                'bahans.id', 'bahans.nama',
                DB::raw('SUM(adjustment_details.qty)qty')
            ])->get()->toArray();
    }

    protected static function _pembelian($tanggal1, $tanggal2)
    {
        return \App\PembelianDetail::join('bahans', 'pembelian_details.relation_id', '=', 'bahans.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->whereBetween('pembelians.tanggal', [$tanggal1, $tanggal2])
            ->where('pembelian_details.type', 'bahan')
            ->groupBy('bahans.id')
            ->select([
                'bahans.id', 'bahans.nama',
                DB::raw('SUM(pembelian_details.stok)qty')
            ])->get()->toArray();
    }

    protected static function _penjualan($tanggal1, $tanggal2)
    {
        return \App\OrderDetailBahan::join('bahans', 'order_detail_bahans.bahan_id', '=', 'bahans.id')
            ->join('order_details', 'order_detail_bahans.order_detail_id', '=', 'order_details.id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.tanggal', [$tanggal1, $tanggal2])
            ->groupBy('bahans.id')
            ->select([
                'bahans.id', 'bahans.nama',
                DB::raw('SUM(order_detail_bahans.qty * (order_details.qty - ifnull(order_detail_returns.qty, 0)))qty')
            ])->get()->toArray();
    }
}
