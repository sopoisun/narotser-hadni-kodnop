<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use Log;
use App\PembelianBayar;
use App\AccountSaldo;
use DB;

class CountPurchaseAccount extends Job implements ShouldQueue
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
        $this->tanggal = $tanggal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Log::info("Handle Purchase Count Job ".$this->tanggal);

        $tanggal    = $this->tanggal;
        $pembelian  = PembelianBayar::where("tanggal", $tanggal)->select([
                DB::raw("SUM(nominal)total")
            ])->groupBy("tanggal")->first();

        if( $pembelian ){
            $total      = $pembelian->total;

            if ( $total >= 1 ) {
                $account = AccountSaldo::where('tanggal', $tanggal)->where('account_id', 1)->first();
                if( !$account ){ //create new
                    AccountSaldo::create([
                        'tanggal'       => $tanggal,
                        'account_id'    => 1,
                        'type'          => 'kredit',
                        'nominal'       => $total,
                    ]);
                }else{ // update value
                    if( $account->nominal != $total ){
                        AccountSaldo::find($account->id)->update([
                            'nominal'   => $total,
                        ]);
                    }
                }
            }
        }

        //Log::info("Handle Purchase Count Job ".$pembelian->total);
    }
}
