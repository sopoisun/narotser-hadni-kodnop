<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Customer;
use App\CustomerPurchase;
use DB;

class CountMemberPurchase extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $memberPurchase     = CustomerPurchase::all();
        $memberPurchaseKey  = array_column($memberPurchase->toArray(), 'customer_id');
        $customers          = Customer::leftJoin(DB::raw("
                (SELECT orders.`id`, orders.`customer_id`, SUM(order_details.`harga_jual` * (order_details.`qty` - ifnull(order_detail_returns.qty, 0)))
                AS total_penjualan FROM orders INNER JOIN order_details ON orders.`id` = order_details.`order_id`
                LEFT JOIN order_detail_returns on order_details.id = order_detail_returns.order_detail_id
                WHERE orders.`state` = 'Closed' GROUP BY orders.`id`)AS temp_orders
            "), 'customers.id', '=', 'temp_orders.customer_id')
                ->whereNotNull('customers.nama')
                ->select([
                    'customers.*', DB::raw('count(temp_orders.id) as jumlah_kunjungan'),
                    DB::raw('sum(temp_orders.total_penjualan)as total')
                ])
                ->groupBy('customers.id')
                ->get();

        foreach ($customers as $customer) {
            if( in_array($customer->id, $memberPurchaseKey) ){
                CustomerPurchase::where('customer_id', $customer->id)
                    ->update([
                        'visit'     => ($customer->jumlah_kunjungan != null) ? $customer->jumlah_kunjungan : 0,
                        'purchase'  => ($customer->total != null) ? $customer->total : 0,
                    ]);
            }else{
                CustomerPurchase::create([
                    'customer_id'   => $customer->id,
                    'visit'         => ($customer->jumlah_kunjungan != null) ? $customer->jumlah_kunjungan : 0,
                    'purchase'      => ($customer->total != null) ? $customer->total : 0,
                ]);
            }
        }
    }
}
