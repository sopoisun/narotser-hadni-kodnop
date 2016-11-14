<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\MutasiStokBahan;
use App\Bahan;
use Log;
use DB;

class JobMutasiBahan extends Job implements ShouldQueue
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
        MutasiStokBahan::where('tanggal', $this->tanggal)->delete();

        $bahans = \App\Bahan::MutasiStok($this->tanggal);

        $inserts = [];
        foreach( $bahans as $bahan ) {
            array_push($inserts, [
                'bahan_id'              => $bahan['id'],
                'before'                => $bahan['before'],
                'pembelian'             => $bahan['pembelian'],
                'penjualan'             => $bahan['penjualan'],
                'adjustment_increase'   => $bahan['adjustment_increase'],
                'adjustment_reduction'  => $bahan['adjustment_reduction'],
                'sisa'                  => $bahan['sisa'],
                'tanggal'               => $this->tanggal
            ]);
        }

        MutasiStokBahan::insert($inserts);
    }
}
