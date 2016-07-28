<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CountSaleAccount;
use Carbon\Carbon;
use Log;
use Validator;

class CountSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sale:count {tanggal?} {to_tanggal?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count Sale ( Auto Account Data)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $validator = Validator::make([
            'tanggal'       => $this->argument('tanggal'),
            'to_tanggal'    => $this->argument('to_tanggal'),
        ], [
            'tanggal'       => 'date',
            'to_tanggal'    => 'date',
        ], [
            'tanggal.date'      => 'param1 harus tanggal (Y-m-d)',
            'to_tanggal.date'   => 'param2 harus tanggal (Y-m-d)',
        ]);

        if( $validator->passes() ){
            if( !$this->argument('tanggal') && !$this->argument('to_tanggal') ){
                $this->actionNow();
            }elseif ( $this->argument('tanggal') && !$this->argument('to_tanggal') ) {
                $this->actionSpecific($this->argument('tanggal'));
                dispatch(new CountSaleAccount($this->argument('tanggal')));
            }elseif ( $this->argument('tanggal') && $this->argument('to_tanggal') ){
                $this->actionRange($this->argument('tanggal'), $this->argument('to_tanggal'));
            }
        }else{
            foreach( $validator->messages()->all() as $m ){
                $this->error($m);
            }
        }

    }

    public function actionNow()
    {
        $tanggal = Carbon::now('Asia/Jakarta');
        $this->info("Hitung penjualan tanggal ".$tanggal->format('d M Y'));
        dispatch(new CountSaleAccount($tanggal->format('Y-m-d')));
    }

    public function actionSpecific($tanggal)
    {
        $tanggal = Carbon::createFromFormat('Y-m-d', $tanggal);
        $this->info("Hitung penjualan tanggal ".$tanggal->format('d M Y'));
        dispatch(new CountSaleAccount($tanggal->format('Y-m-d')));
    }

    public function actionRange($tanggal, $to_tanggal)
    {
        $tanggal    = Carbon::createFromFormat('Y-m-d', $tanggal);
        $to_tanggal = Carbon::createFromFormat('Y-m-d', $to_tanggal);

        $this->info("Hitung penjualan dari tanggal ".$tanggal->format('d M Y').' s/d '.$to_tanggal->format('d M Y'));

        $start  = $tanggal->copy();
        $end    = $to_tanggal->copy();

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        foreach( $dates as $date ) {
            dispatch(new CountSaleAccount($date->format('Y-m-d')));
        }
    }
}
