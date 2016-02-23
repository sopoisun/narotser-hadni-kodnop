<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Produk extends Model
{
    protected $fillable = ['nama', 'satuan', 'konsinyasi', 'supplier_id', 'hpp',
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
                DB::raw("(SELECT order_details.`produk_id`,SUM(order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty
                    FROM order_details
                    LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                    LEFT JOIN order_detail_bahans ON order_details.`id` = order_detail_bahans.`order_detail_id`
                    INNER JOIN orders ON order_details.`order_id` = orders.`id`
                    WHERE order_detail_bahans.`id` IS NULL
                    AND orders.`state` = 'Closed'
                    GROUP BY order_details.`produk_id`) penjualan"),
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
            ->join(DB::raw("(SELECT produks.`id`, produks.`nama`, produks.`use_mark_up`, produks.`qty_warning`, IF(produk_details.`id` IS NULL, 'Tidak', 'Ya')use_bahan,
                    IF(produks.`use_mark_up` = 'Tidak', produks.`hpp`, SUM(bahans.`harga`*produk_details.`qty`))hpp,
                    IF(produks.`use_mark_up` = 'Tidak', produks.`harga`, SUM(bahans.`harga`*produk_details.`qty`)+
                    (SUM(bahans.`harga`*produk_details.`qty`)*(produks.`mark_up`/100)))harga_jual,
                    IF( produks.`use_mark_up` = 'Tidak', ROUND(((produks.`harga`-produks.`hpp`)/produks.`hpp`)*100),
                    produks.`mark_up`)laba_procentage
                    FROM produks
                    LEFT JOIN produk_details ON produks.`id` = produk_details.`produk_id`
                    LEFT JOIN bahans ON produk_details.`bahan_id` = bahans.`id`
                    GROUP BY produks.`id`)as produk_temp"),
                function($join){
                    $join->on('produks.id', '=', 'produk_temp.id');
                }
            )
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
                DB::raw("(SELECT order_details.`produk_id`,SUM(order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty
                    FROM order_details
                    LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                    LEFT JOIN order_detail_bahans ON order_details.`id` = order_detail_bahans.`order_detail_id`
                    INNER JOIN orders ON order_details.`order_id` = orders.`id`
                    WHERE order_detail_bahans.`id` IS NULL
                    AND orders.`state` = 'Closed'
                    GROUP BY order_details.`produk_id`) penjualan"),
                function($join){
                    $join->on('produks.id', '=', 'penjualan.produk_id');
                }
            )
            ->select([
                'produk_temp.*',
                DB::raw('produk_kategoris.nama as nama_kategori'),
                DB::raw('ifnull(temp_pembelian.stok, 0)as stok_pembelian'),
                DB::raw('ifnull(penjualan.qty, 0)as penjualan_stok'),
                DB::raw('ifnull(adjustment_increase.qty, 0)as adjustment_increase_stok'),
                DB::raw('ifnull(adjustment_reduction.qty, 0)as adjustment_reduction_stok'),
                DB::raw('(( ifnull(temp_pembelian.stok, 0) + ifnull(adjustment_increase.qty, 0) ) - ( ifnull(penjualan.qty, 0) + ifnull(adjustment_reduction.qty, 0) ))sisa_stok'),
            ])
            ->where('produks.active', 1)
            ->groupBy('produks.id');
    }
}
