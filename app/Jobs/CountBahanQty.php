<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\StokBahan;
use App\Bahan;

class CountBahanQty extends Job implements ShouldQueue
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
        $oldStok    = StokBahan::all();
        $oldStokKey = array_column($oldStok->toArray(), 'bahan_id');
        $bahans     = Bahan::stok()->orderBy('bahans.id')->get();

        foreach ( $bahans as $bahan ) {
            if( in_array($bahan->id, $oldStokKey) ){
                StokBahan::where('bahan_id', $bahan->id)->update([
                    'stok'      => $bahan->sisa_stok,
                ]);
            }else{
                StokBahan::create([
                    'bahan_id'  => $bahan->id,
                    'stok'      => $bahan->sisa_stok,
                ]);
            }
        }
    }
}
