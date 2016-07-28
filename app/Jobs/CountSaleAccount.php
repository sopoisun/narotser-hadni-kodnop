<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Order;
use App\AccountSaldo;
use Log;
use DB;

class CountSaleAccount extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $tanggal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tanggal)
    {
        //Log::info("Run In Constructor => ".$tanggal);
        $this->tanggal = $tanggal;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Log::info("In Handle ".$this->tanggal);

        $tanggal    = $this->tanggal;
        $reports    = Order::ReportByDate($tanggal);
        $reports    = collect($reports)->groupBy('_type_bayar');

        $totalCash  = isset($reports['tunai']) ? collect($reports['tunai'])->sum('jumlah') : 0;

        $mergeDebitCcard = [];
        $mergeDebitCcard = isset($reports['debit']) ? array_merge($mergeDebitCcard, $reports['debit']->toArray()) : array_merge($mergeDebitCcard, []);
        $mergeDebitCcard = isset($reports['credit_card']) ? array_merge($mergeDebitCcard, $reports['credit_card']->toArray()) : array_merge($mergeDebitCcard, []);

        $bayarBank = collect($mergeDebitCcard)->groupBy('_bank_id');

        $accountSaldo = AccountSaldo::join('accounts', 'account_saldos.account_id', '=', 'accounts.id')
            ->leftJoin('banks', 'account_saldos.relation_id', '=', 'banks.id')
            ->where('tanggal', $tanggal)->where('account_id', 2)
            ->select([
                'account_saldos.*',
                DB::raw('IFNULL(account_saldos.relation_id, "cash")_relation_id'),
            ])
            ->get()->groupBy("_relation_id");

        $totals         = [];
        if( $totalCash > 0 ){
            $totals['cash'] = $totalCash;
        }
        foreach ($bayarBank as $key => $val) {
            $totals[$key] = $val->sum('jumlah');
        }

        $actions = [];
        $totalKeys = array_keys($totals);
        foreach($totalKeys as $bank_id){
            $nominal = $totals[$bank_id];
            if ( !isset($accountSaldo[$bank_id]) ){ // create new
                $inputs = [
                    'tanggal'       => $tanggal,
                    'account_id'    => 2,
                    'type'          => 'debet',
                    'nominal'       => $nominal,
                ];

                if( $bank_id != 'cash' ){
                    $inputs += ['relation_id' => $bank_id];
                }

                AccountSaldo::create($inputs);
                //$actions[$bank_id] = $inputs;
            }else{ // update value
                $row = $accountSaldo[$bank_id][0];
                $row = AccountSaldo::find($row['id']);

                $row->update(['nominal'   => $nominal]);

                //$actions[$bank_id]  = $row;
            }
        }

        //return $actions;
        //Log::info('Queue End @'.Carbon::now('Asia/Jakarta'));
    }
}
