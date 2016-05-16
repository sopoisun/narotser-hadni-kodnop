<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Produk extends Model
{
    protected $fillable = ['nama', 'satuan', 'satuan_beli', 'konsinyasi', 'supplier_id', 'hpp',
                            'harga', 'use_mark_up', 'mark_up', 'produk_kategori_id',
                            'qty_warning', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function kategori()
    {
        return $this->belongsTo('App\ProdukKategori', 'produk_kategori_id', 'id');
    }

    public function detail()
    {
        return $this->hasMany('App\ProdukDetail', 'produk_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
    }

    public function pembelianDetail()
    {
        return $this->hasOne('App\PembelianDetail', 'relation_id', 'id');
    }

    public function adjustmentDetail()
    {
        return $this->hasOne('App\AdjustmentDetail', 'relation_id', 'id');
    }

    public function orderDetail()
    {
        return $this->hasOne('App\OrderDetail', 'produk_id', 'id');
    }

    public static function stok()
    {
        return self::leftJoin('produk_details', 'produks.id', '=', 'produk_details.produk_id')
            ->leftJoin(DB::raw("(SELECT pembelian_details.`relation_id`, SUM(pembelian_details.`stok`)stok
                    FROM pembelian_details WHERE pembelian_details.`type` = 'produk'
                    GROUP BY pembelian_details.`relation_id`)temp_pembelian"),
                    function($join){
                        $join->on('produks.id', '=', 'temp_pembelian.relation_id');
                    }
            )
            ->leftJoin(DB::raw("(SELECT adjustment_details.`relation_id`, SUM(adjustment_details.`qty`)AS qty
                    FROM adjustment_details WHERE adjustment_details.`state`= 'increase'
                    AND adjustment_details.`type` = 'produk'
                    GROUP BY adjustment_details.`relation_id`)as adjustment_increase"),
                function($join){
                    $join->on('produks.id', '=', 'adjustment_increase.relation_id');
                }
            )
            ->leftJoin(DB::raw("(SELECT adjustment_details.`relation_id`, SUM(adjustment_details.`qty`)AS qty
                    FROM adjustment_details WHERE adjustment_details.`state`= 'reduction'
                    AND adjustment_details.`type` = 'produk'
                    GROUP BY adjustment_details.`relation_id`)as adjustment_reduction"),
                function($join){
                    $join->on('produks.id', '=', 'adjustment_reduction.relation_id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT produks.`id` AS produk_id, SUM(order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0)) qty
                    FROM produks
                    LEFT JOIN produk_details ON produks.`id` = produk_details.`produk_id`
                    LEFT JOIN order_details ON produks.`id` = order_details.`produk_id`
                    LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                    INNER JOIN orders ON order_details.`order_id` = orders.`id`
                    WHERE produk_details.`id` IS NULL AND orders.`state` in ('Closed', 'On Going')
                    GROUP BY produks.`id`) penjualan"),
                function($join){
                    $join->on('produks.id', '=', 'penjualan.produk_id');
                }
            )
            ->select([
                'produks.*',
                DB::raw('ifnull(temp_pembelian.stok, 0)as stok_pembelian'),
                DB::raw('ifnull(penjualan.qty, 0)as penjualan_stok'),
                DB::raw('ifnull(adjustment_increase.qty, 0)as adjustment_increase_stok'),
                DB::raw('ifnull(adjustment_reduction.qty, 0)as adjustment_reduction_stok'),
                DB::raw('(( ifnull(temp_pembelian.stok, 0) + ifnull(adjustment_increase.qty, 0) ) - ( ifnull(penjualan.qty, 0) + ifnull(adjustment_reduction.qty, 0) ))sisa_stok'),
            ])
            ->where('produks.active', 1)
            ->whereNull('produk_details.id')
            ->groupBy('produks.id');
            /*->orderBy('produks.id')
            ->get();*/
    }

    public static function allWithStokAndPrice()
    {
        return self::leftJoin('produk_details', 'produks.id', '=', 'produk_details.produk_id')
            ->join('produk_kategoris', 'produks.produk_kategori_id', '=', 'produk_kategoris.id')
            ->join(DB::raw("(SELECT produks.`id`, produks.`nama`, produks.`use_mark_up`, produks.`mark_up`, produks.`qty_warning`,
                IF(produk_details.`id` IS NULL, 'Tidak', 'Ya') use_bahan,
                IF(produk_details.`id` IS NULL, ROUND(produks.`hpp`), ROUND(SUM(bahans.`harga` * produk_details.`qty`))) hpp,
                IF(produks.`use_mark_up` = 'Tidak', produks.`harga`, IF(produk_details.`id` IS NULL,
                ROUND(produks.`hpp` + (produks.`hpp`*(produks.`mark_up`/100))),
                ROUND(SUM(bahans.`harga` * produk_details.`qty`) + (SUM(bahans.`harga` * produk_details.`qty`)*(produks.`mark_up`/100))))) harga_jual,
                IFNULL(IF(produks.`use_mark_up` = 'Tidak', IF(produk_details.`id` IS NULL,ROUND(((produks.`harga`- produks.`hpp`)/produks.`hpp`)*100),
                ROUND(((produks.`harga`- SUM(bahans.`harga` * produk_details.`qty`))/SUM(bahans.`harga` * produk_details.`qty`))*100)), produks.`mark_up`)
                , 0) laba_procentage FROM produks LEFT JOIN produk_details ON produks.`id` = produk_details.`produk_id` LEFT JOIN bahans
                ON produk_details.`bahan_id` = bahans.`id` GROUP BY produks.`id` )as produk_temp"),
                function($join){
                    $join->on('produks.id', '=', 'produk_temp.id');
                }
            )
            ->select([
                'produk_temp.*',
                DB::raw('produk_kategoris.nama as nama_kategori'),
            ])
            ->where('produks.active', 1)
            ->groupBy('produks.id');
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
        $produks = self::leftJoin('produk_details', 'produks.id', '=', 'produk_details.produk_id')
            ->whereNull('produk_details.id')
            ->select(['produks.id', 'produks.nama', 'produks.satuan'])->get();

        $CTanggal = Carbon::createFromFormat('Y-m-d h:i:s', $tanggal.' 00:00:00');
        $CYesterday = $CTanggal->copy()->addDays(-1);
        $yesterday  = $CYesterday->format('Y-m-d');

        $adjustmentIncreaseBefore = [];
        $firstAdjustmentIncrease = \App\AdjustmentDetail::where('type', 'produk')
            ->where('state', 'increase')->with('adjustment')->first();
        if( $firstAdjustmentIncrease ){
            if( $firstAdjustmentIncrease->adjustment->tanggal->lte($CYesterday) ){
                $adjustmentIncreaseBefore = self::_adjustmentIncrease_Stok($firstAdjustmentIncrease->adjustment->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $adjustmentReductionBefore = [];
        $firstAdjustmentReduction = \App\AdjustmentDetail::where('type', 'produk')
            ->where('state', 'reduction')->with('adjustment')->first();
        if( $firstAdjustmentReduction ){
            if( $firstAdjustmentReduction->adjustment->tanggal->lte($CYesterday) ){
                $adjustmentReductionBefore = self::_adjustmentReduction_Stok($firstAdjustmentReduction->adjustment->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $pembelianBefore = [];
        $firstPembelian = \App\PembelianDetail::where('pembelian_details.type', 'produk')
            ->with('pembelian')->first();
        if( $firstPembelian ){
            if( $firstPembelian->pembelian->tanggal->lte($CYesterday) ){
                $pembelianBefore = self::_pembelian($firstPembelian->pembelian->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $penjualanBefore = [];
        $firstPenjualan = \App\OrderDetail::join(DB::raw("(SELECT produks.`id`, produks.`nama`
                FROM produks LEFT JOIN produk_details ON produks.`id` = produk_details.`produk_id`
                WHERE produk_details.`id` IS NULL GROUP BY produks.`id`)temp_produk"),
                'order_details.produk_id', '=', 'temp_produk.id')
            ->with('order')->select(['temp_produk.nama', 'order_details.*'])->first();
        if( $firstPenjualan ){
            if( $firstPenjualan->order->tanggal->lte($CYesterday) ){
                $penjualanBefore = self::_penjualan($firstPenjualan->order->tanggal->format('Y-m-d'), $yesterday);
            }
        }

        $display = [];
        foreach($produks as $produk){
            $temp = [];
            $sum = [];

            $idx = array_search($produk['id'], array_column($adjustmentIncreaseBefore, 'id'));
            if (false !== $idx) {
                $sum[] = $adjustmentIncreaseBefore[$idx]['qty'];
            }

            $idx = array_search($produk['id'], array_column($adjustmentReductionBefore, 'id'));
            if (false !== $idx) {
                $sum[] = -abs($adjustmentReductionBefore[$idx]['qty']);
            }

            $idx = array_search($produk['id'], array_column($pembelianBefore, 'id'));
            if (false !== $idx) {
                $sum[] = $pembelianBefore[$idx]['qty'];
            }

            $idx = array_search($produk['id'], array_column($penjualanBefore, 'id'));
            if (false !== $idx) {
                $sum[] = -abs($penjualanBefore[$idx]['qty']);
            }

            $display[] = $produk->toArray() + ['before' => array_sum($sum)];
        }

        return $display;
    }

    protected static function _adjustmentIncrease_Stok($tanggal1, $tanggal2)
    {
        return \App\AdjustmentDetail::join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->whereBetween('adjustments.tanggal', [$tanggal1, $tanggal2])
            ->where('type', 'produk')
            ->where('state', 'increase')
            ->groupBy('produks.id')
            ->select([
                'produks.id', 'produks.nama',
                DB::raw('SUM(adjustment_details.qty)qty')
            ])->get()->toArray();
    }

    protected static function _adjustmentReduction_Stok($tanggal1, $tanggal2)
    {
        return \App\AdjustmentDetail::join('produks', 'adjustment_details.relation_id', '=', 'produks.id')
            ->join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->whereBetween('adjustments.tanggal', [$tanggal1, $tanggal2])
            ->where('type', 'produk')
            ->where('state', 'reduction')
            ->groupBy('produks.id')
            ->select([
                'produks.id', 'produks.nama',
                DB::raw('SUM(adjustment_details.qty)qty')
            ])->get()->toArray();
    }

    protected static function _pembelian($tanggal1, $tanggal2)
    {
        return \App\PembelianDetail::join('produks', 'pembelian_details.relation_id', '=', 'produks.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->whereBetween('pembelians.tanggal', [$tanggal1, $tanggal2])
            ->where('pembelian_details.type', 'produk')
            ->groupBy('produks.id')
            ->select([
                'produks.id', 'produks.nama',
                DB::raw('SUM(pembelian_details.stok)qty')
            ])->get()->toArray();
    }

    protected static function _penjualan($tanggal1, $tanggal2)
    {
        return \App\OrderDetail::join(DB::raw("(SELECT produks.`id`, produks.`nama`
                FROM produks LEFT JOIN produk_details ON produks.`id` = produk_details.`produk_id`
                WHERE produk_details.`id` IS NULL GROUP BY produks.`id`)temp_produk"),
                'order_details.produk_id', '=', 'temp_produk.id')
            ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.tanggal', [$tanggal1, $tanggal2])
            ->groupBy('temp_produk.id')
            ->select([
                'temp_produk.id', 'temp_produk.nama',
                DB::raw('SUM(order_details.qty - ifnull(order_detail_returns.qty, 0))qty')
            ])->get()->toArray();
    }
}
