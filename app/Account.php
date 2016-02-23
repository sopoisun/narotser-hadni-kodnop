<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Account extends Model
{
    protected $fillable = ['nama_akun', 'data_state', 'type', 'relation', 'can_edit', 'active'];
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

    public function reports()
    {
        return $this->belongsToMany(Report::class);
    }

    public function assignReport($report)
    {
        if (is_string($report)) {
            $report = Role::where('key', $report)->first();
        }
        return $this->reports()->attach($report);
    }

    public function revokeReport($report)
    {
        if (is_string($report)) {
            $report = Role::where('key', $report)->first();
        }
        return $this->reports()->detach($report);
    }

    public static function TotalPenjualan($where="", $groupBy="")
    {
        $query = "SELECT orders.`id`, orders.`tanggal`, ifnull(SUM(
                ((temp_order_details.total+temp_order_places.total+order_bayars.`service_cost`) +
                ROUND((temp_order_details.total+temp_order_places.total+order_bayars.`service_cost`) * (order_taxes.`procentage`/ 100)) +
                IFNULL(ROUND(((temp_order_details.total+temp_order_places.total+order_bayars.`service_cost`) +
                    ROUND((temp_order_details.total+temp_order_places.total+order_bayars.`service_cost`) * (order_taxes.`procentage`/ 100))) *
                    (order_bayar_banks.`tax_procentage` / 100)),0))
            	- order_bayars.`diskon`
            ), 0)total
            FROM orders INNER JOIN order_taxes ON orders.`id` = order_taxes.`order_id`
            INNER JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
            LEFT JOIN  order_bayar_banks ON orders.`id` = order_bayar_banks.`order_id`
            INNER JOIN (
                SELECT orders.`id` AS order_id, SUM(order_places.`harga`)total
                FROM orders INNER JOIN order_places ON orders.`id` = order_places.`order_id`
                INNER JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
                LEFT JOIN  order_bayar_banks ON orders.`id` = order_bayar_banks.`order_id`
                WHERE $where orders.`state` = 'Closed'
                GROUP BY orders.`id`
            )temp_order_places ON orders.`id` = temp_order_places.order_id
            INNER JOIN (
                SELECT orders.`id` AS order_id, SUM(order_details.`harga_jual` * (order_details.`qty` - IFNULL(order_detail_returns.`qty`, 0)))total
                FROM orders INNER JOIN order_details ON orders.`id` = order_details.`order_id`
                LEFT JOIN order_detail_returns ON order_details.`id` = order_detail_returns.`order_detail_id`
                INNER JOIN order_bayars ON orders.`id` = order_bayars.`order_id`
                LEFT JOIN  order_bayar_banks ON orders.`id` = order_bayar_banks.`order_id`
                WHERE $where orders.`state` = 'Closed'
                GROUP BY orders.`id`
            )temp_order_details ON orders.`id` = temp_order_details.order_id
            WHERE $where orders.`state` = 'Closed' $groupBy";
        return DB::select($query);
    }

    public static function TotalPembelian($where="")
    {
        $query = "SELECT pembelian_bayars.tanggal, ifnull(SUM(pembelian_bayars.`nominal`), 0)total FROM pembelian_bayars
            WHERE $where";
        return DB::select($query);
    }

    public static function TotalAccountSaldo($columnCondition, $where="", $typeReport='jurnal')
    {
        $query = "SELECT ifnull(SUM($columnCondition), 0)total
            FROM account_saldos
            INNER JOIN accounts ON account_saldos.`account_id` = accounts.`id`
            LEFT JOIN (
                SELECT accounts.`id` AS account_id, accounts.`nama_akun`, reports.display
                FROM accounts
                INNER JOIN account_report ON accounts.`id` = account_report.`account_id`
                INNER JOIN reports ON account_report.`report_id` = reports.id
                WHERE reports.key = '$typeReport'
            )AS temp_report ON accounts.`id` = temp_report.account_id
            WHERE $where temp_report.account_id IS NOT NULL";
        return DB::select($query);
    }
}
