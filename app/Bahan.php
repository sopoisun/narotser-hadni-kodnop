<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Bahan extends Model
{
    protected $fillable = ['nama', 'satuan', 'harga'];
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

    public static function stok()
    {
        return self::leftJoin('pembelian_details', 'bahans.id', '=', 'pembelian_details.relation_id')
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
                DB::raw("(SELECT order_detail_bahans.`bahan_id`, SUM(order_detail_bahans.`qty`*order_details.`qty`)qty
                    FROM order_detail_bahans
                    INNER JOIN order_details ON order_detail_bahans.`order_detail_id` = order_details.`id`
                    INNER JOIN orders ON order_details.`order_id` = orders.`id`
                    WHERE orders.`state` = 'Closed'
                    GROUP BY order_detail_bahans.`bahan_id`) penjualan"),
                function($join){
                    $join->on('bahans.id', '=', 'penjualan.bahan_id');
                }
            )
            ->select([
                'bahans.*',
                DB::raw('SUM(ifnull(pembelian_details.stok, 0))as stok_pembelian'),
                DB::raw('ifnull(penjualan.qty, 0)as penjualan_stok'),
                DB::raw('ifnull(adjustment_increase.qty, 0)as adjustment_increase_stok'),
                DB::raw('ifnull(adjustment_reduction.qty, 0)as adjustment_reduction_stok'),
                DB::raw('(( SUM(ifnull(pembelian_details.stok, 0)) + ifnull(adjustment_increase.qty, 0) ) - ( ifnull(penjualan.qty, 0) + ifnull(adjustment_reduction.qty, 0) ))sisa_stok'),
            ])
            ->groupBy('bahans.id');
            /*->orderBy('bahans.id')
            ->get();*/
    }
}
