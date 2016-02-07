<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Account extends Model
{
    protected $fillable = ['nama_akun', 'data_state', 'type', 'relation', 'can_edit'];
    protected $hidden   = ['created_at', 'updated_at'];
    public static $data_states  = [
        'input' => 'Input Manual',
        'auto'  => 'Otomatis',
    ];
    public static $types        = [
        'debet'  => 'Debet',
        'kredit' => 'Kredit',
    ];
    public static $can_edit     = [
        'Ya'    => 'Ya',
        'Tidak' => 'Tidak',
    ];

    public function saldo()
    {
        return $this->hasMany('App\AccountSaldo', 'account_id', 'id');
    }

    public static function TotalPenjualan($where="", $groupBy="")
    {
        $query = "SELECT orders.`id`, orders.`tanggal`, ifnull(SUM(
            	((temp_order_details.total+temp_order_places.total) +
            	ROUND((temp_order_details.total+temp_order_places.total) * (order_taxes.`procentage`/ 100)) +
            	ROUND(((temp_order_details.total+temp_order_places.total) +
            		ROUND((temp_order_details.total+temp_order_places.total) * (order_taxes.`procentage`/ 100))) *
            		(order_bayar_banks.`tax_procentage` / 100)))
            	- order_bayars.`diskon`
            ), 0)total
            FROM orders INNER JOIN order_taxes ON orders.`id` = order_taxes.`order_id`
            INNER JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
            LEFT JOIN  order_bayar_banks ON orders.`id` = order_bayar_banks.`order_id`
            INNER JOIN (
                SELECT orders.`id` AS order_id, SUM(order_places.`harga`)total
                FROM orders INNER JOIN order_places ON orders.`id` = order_places.`order_id`
                INNER JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
                WHERE $where orders.`state` = 'Closed'
                GROUP BY orders.`id`
            )temp_order_places ON orders.`id` = temp_order_places.order_id
            INNER JOIN (
                SELECT orders.`id` AS order_id, SUM(order_details.`harga_jual` * (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0)))total
                FROM orders INNER JOIN order_details ON orders.`id` = order_details.`order_id`
                LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                INNER JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
                WHERE $where orders.`state` = 'Closed'
                GROUP BY orders.`id`
            )temp_order_details ON orders.`id` = temp_order_details.order_id
            WHERE $where orders.`state` = 'Closed' $groupBy";
        return DB::select($query);
    }

    public static function TotalPembelian($where="")
    {
        $query = "SELECT pembelian_bayars.tanggal, SUM(pembelian_bayars.`nominal`)total FROM pembelian_bayars
            WHERE $where";
        return DB::select($query);
    }

    public static function TotalAccountSaldo($columnCondition, $where="")
    {
        $query = "SELECT SUM($columnCondition)total FROM account_saldos WHERE $where";
        return DB::select($query);
    }
}
