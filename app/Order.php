<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    protected $fillable = ['nota', 'tanggal', 'karyawan_id', 'customer_id', 'state'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];

    public function bayar()
    {
        return $this->hasOne('App\OrderBayar', 'order_id', 'id');
    }

    public function bayarBank()
    {
        return $this->hasOne('App\OrderBayarBank', 'order_id', 'id');
    }

    public function detail()
    {
        return $this->hasMany('App\OrderDetail', 'order_id', 'id');
    }

    public function merge()
    {
        return $this->hasOne('App\OrderMerge', 'order_id', 'id');
    }

    public function place()
    {
        return $this->hasMany('App\OrderPlace', 'order_id', 'id');
    }

    public function tax()
    {
        return $this->hasOne('App\OrderTax', 'order_id', 'id');
    }

    public function cancel()
    {
        return $this->hasOne('App\OrderCancel', 'order_id', 'id');
    }

    public function karyawan() // For Kasir
    {
        return $this->belongsTo('App\Karyawan', 'karyawan_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public static function ReportByDate($date)
    {
        $query = "SELECT orders.`id`, orders.`nota`, orders.`state`,
            taxes.`type`, IF(orders.`state` = 'Closed', CONCAT(taxes.`type`, '(', order_taxes.`procentage`,'%)'),'--')type_tax,
            IF(order_bayars.`type_bayar` IS NOT NULL, IF(order_bayars.`type_bayar` != 'tunai',
            CONCAT(REPLACE(order_bayars.`type_bayar`, '_', ' '), ' ',banks.`nama_bank`, '(', `order_bayar_banks`.`tax_procentage`, '%)') ,'Bayar Tunai'),'--')type_bayar,
            IFNULL(order_taxes.`procentage`, 0)AS tax_procentage,
            IFNULL(order_bayar_banks.`tax_procentage`, 0)AS tax_bayar_procentage,
            IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0)total_penjualan,
            IFNULL(temp_order_places.reservasi, 0) AS total_reservasi,
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))pajak,
            ROUND(
            ((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) *
            (IFNULL(order_bayar_banks.`tax_procentage`, 0)/100)
            )pajak_pembayaran,
            (((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) +
            ROUND(
            ((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) *
            (IFNULL(order_bayar_banks.`tax_procentage`, 0)/100)
            ))total_akhir,
            IFNULL(order_bayars.`diskon`, 0)diskon,
            ((((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) +
            ROUND(
            ((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) *
            (IFNULL(order_bayar_banks.`tax_procentage`, 0)/100)
            )) - IFNULL(order_bayars.`diskon`, 0))jumlah,
            IFNULL(SUM(temp_order_details.hpp * temp_order_details.qty), 0)total_hpp
            FROM orders
            LEFT JOIN order_taxes ON orders.`id` = order_taxes.`order_id`
            LEFT JOIN taxes ON order_taxes.`tax_id` = taxes.`id`
            LEFT JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
            LEFT JOIN order_bayar_banks ON orders.`id` = order_bayar_banks.`order_id`
            LEFT JOIN banks ON order_bayar_banks.`bank_id` = banks.`id`
            LEFT JOIN (
            	SELECT orders.`id` AS order_id, SUM(order_places.`harga`)reservasi
            	FROM orders
            	INNER JOIN order_places ON orders.`id` = order_places.`order_id`
            	WHERE orders.`tanggal` = '$date'
            	AND orders.`state` = 'Closed'
            	GROUP BY orders.`id`
            )temp_order_places ON orders.`id` = temp_order_places.order_id
            LEFT JOIN (
            	SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            	IF(order_details.`use_mark_up` = 'Tidak', order_details.`hpp`, SUM(order_detail_bahans.`harga` * ( order_detail_bahans.`qty`)))hpp,
            	order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
	            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            	order_details.`use_mark_up`, order_details.`mark_up`
            	FROM order_details
            	LEFT JOIN order_detail_bahans ON order_details.id = order_detail_bahans.`order_detail_id`
                LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            	INNER JOIN orders ON order_details.`order_id` = orders.`id`
            	WHERE orders.`tanggal` = '$date'
            	AND orders.`state` = 'Closed'
            	GROUP BY order_details.`id`
            )temp_order_details ON orders.`id` = temp_order_details.order_id
            WHERE orders.`tanggal` = '$date'
            GROUP BY orders.`id`";
        return DB::select($query);
    }

    // $condition ex : "SUBSTRING(orders.`tanggal`, 1, 7) = '$bulan'"
    // $groupBy ex : "GROUP BY tanggal"
    public static function ReportGroup($condition, $groupBy, $key = 'tanggal')
    {
        $query = "SELECT $key, SUM(total_penjualan)total_penjualan, SUM(total_reservasi)total_reservasi,
            SUM(pajak)pajak, SUM(pajak_pembayaran)pajak_pembayaran, SUM(total_akhir)total_akhir, SUM(diskon)diskon,
            SUM(jumlah)jumlah, SUM(total_hpp)total_hpp
            FROM
            (
            SELECT orders.`id`, orders.`tanggal`, orders.`nota`, orders.`state`,
            taxes.`type`, IF(orders.`state` = 'Closed', CONCAT(taxes.`type`, '(', order_taxes.`procentage`,'%)'),'--')type_tax,
            IF(order_bayars.`type_bayar` IS NOT NULL, IF(order_bayars.`type_bayar` != 'tunai',
            CONCAT(REPLACE(order_bayars.`type_bayar`, '_', ' '), ' ',banks.`nama_bank`, '(', `order_bayar_banks`.`tax_procentage`, '%)') ,'Bayar Tunai'),'--')type_bayar,
            IFNULL(order_taxes.`procentage`, 0)AS tax_procentage,
            IFNULL(order_bayar_banks.`tax_procentage`, 0)AS tax_bayar_procentage,
            IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0)total_penjualan,
            IFNULL(temp_order_places.reservasi, 0) AS total_reservasi,
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))pajak,
            ROUND(
            ((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) *
            (IFNULL(order_bayar_banks.`tax_procentage`, 0)/100)
            )pajak_pembayaran,
            (((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) +
            ROUND(
            ((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) *
            (IFNULL(order_bayar_banks.`tax_procentage`, 0)/100)
            ))total_akhir,
            IFNULL(order_bayars.`diskon`, 0)diskon,
            ((((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) +
            ROUND(
            ((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) +
            ROUND((IFNULL(SUM(temp_order_details.harga_jual * temp_order_details.qty), 0) + IFNULL(temp_order_places.reservasi, 0)) *
            (IFNULL(order_taxes.`procentage`, 0)/100))) *
            (IFNULL(order_bayar_banks.`tax_procentage`, 0)/100)
            )) - IFNULL(order_bayars.`diskon`, 0))jumlah,
            IFNULL(SUM(temp_order_details.hpp * temp_order_details.qty), 0)total_hpp
            FROM orders
            LEFT JOIN order_taxes ON orders.`id` = order_taxes.`order_id`
            LEFT JOIN taxes ON order_taxes.`tax_id` = taxes.`id`
            LEFT JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
            LEFT JOIN order_bayar_banks ON orders.`id` = order_bayar_banks.`order_id`
            LEFT JOIN banks ON order_bayar_banks.`bank_id` = banks.`id`
            LEFT JOIN (
            	SELECT orders.`id` AS order_id, SUM(order_places.`harga`)reservasi
            	FROM orders
            	INNER JOIN order_places ON orders.`id` = order_places.`order_id`
            	WHERE $condition
            	AND orders.`state` = 'Closed'
            	GROUP BY orders.`id`
            )temp_order_places ON orders.`id` = temp_order_places.order_id
            LEFT JOIN (
            	SELECT order_details.id, order_details.`order_id`, order_details.`produk_id`,
            	IF(order_details.`use_mark_up` = 'Tidak', order_details.`hpp`, SUM(order_detail_bahans.`harga` * ( order_detail_bahans.`qty`)))hpp,
            	order_details.`harga_jual`, order_details.`qty` AS qty_ori, IFNULL(order_detail_returns.`qty`, 0)qty_return,
	            (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0))qty,
            	order_details.`use_mark_up`, order_details.`mark_up`
            	FROM order_details
            	LEFT JOIN order_detail_bahans ON order_details.id = order_detail_bahans.`order_detail_id`
                LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
            	INNER JOIN orders ON order_details.`order_id` = orders.`id`
            	WHERE $condition
            	AND orders.`state` = 'Closed'
            	GROUP BY order_details.`id`
            )temp_order_details ON orders.`id` = temp_order_details.order_id
            WHERE $condition
            GROUP BY orders.`id`
        )laporan_perbulan $groupBy";

        return DB::select($query);
    }
}
