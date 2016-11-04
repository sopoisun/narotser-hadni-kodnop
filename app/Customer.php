<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Customer extends Model
{
    protected $fillable = ['kode', 'nama', 'no_hp', 'alamat', 'keterangan', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->hasMany('App\Order', 'customer_id', 'id');
    }

    public static function lastCustomerID()
    {
        return self::where('active', 1)
            ->select([
                DB::raw("CAST(kode AS UNSIGNED)kode")
            ])
            ->orderBy(DB::raw("CAST(kode AS UNSIGNED)"), "DESC")->first();
    }

    public static function Report($tanggal1, $tanggal2="")
    {
        if( $tanggal2 == "" ){
            $tanggal2 = $tanggal1;
        }

        $where = "(orders.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ) AND";

        return self::join(DB::raw("
                    (SELECT orders.`id`, orders.`customer_id`, orders.`tanggal`,
                    SUM(order_details.`harga_jual` * ( order_details.`qty` - IFNULL(order_detail_returns.`qty`,0 )))total
                    FROM orders
                    INNER JOIN order_details ON orders.`id` = order_details.`order_id`
                    LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                    WHERE $where orders.`customer_id` IS NOT NULL
                    GROUP BY orders.`id`)tbl_transaksi
                "), "customers.id", "=", "tbl_transaksi.customer_id")
            ->groupBy('customers.id')
            ->select([
                'customers.id',
                'customers.kode',
                'customers.nama',
                DB::raw('COUNT(tbl_transaksi.id)jumlah_transaksi'),
                DB::raw('SUM(tbl_transaksi.total)as total')
            ])
            ->get();
    }
}
