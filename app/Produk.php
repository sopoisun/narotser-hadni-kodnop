<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Produk extends Model
{
    protected $fillable = ['nama', 'satuan', 'konsinyasi', 'supplier_id', 'hpp',
                            'harga', 'use_mark_up', 'mark_up', 'produk_kategori_id'];
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
            ->leftJoin('pembelian_details', 'produks.id', '=', 'pembelian_details.relation_id')
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
                DB::raw("(SELECT order_details.`produk_id`, SUM(order_details.`qty`)qty
                    FROM order_details
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
                DB::raw('SUM(ifnull(pembelian_details.stok, 0))as stok_pembelian'),
                DB::raw('ifnull(penjualan.qty, 0)as penjualan_stok'),
                DB::raw('ifnull(adjustment_increase.qty, 0)as adjustment_increase_stok'),
                DB::raw('ifnull(adjustment_reduction.qty, 0)as adjustment_reduction_stok'),
                DB::raw('(( SUM(ifnull(pembelian_details.stok, 0)) + ifnull(adjustment_increase.qty, 0) ) - ( ifnull(penjualan.qty, 0) + ifnull(adjustment_reduction.qty, 0) ))sisa_stok'),
            ])
            ->whereNull('produk_details.id')
            ->groupBy('produks.id');
            /*->orderBy('produks.id')
            ->get();*/
    }
}
