<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\StokProduk;
use App\Produk;

class CountProdukQty extends Job implements ShouldQueue
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
        $oldStok    = StokProduk::all();
        $oldStokKey = array_column($oldStok->toArray(), 'produk_id');
        $produks    = Produk::stok()->orderBy('produks.id')->get();

        foreach ( $produks as $produk ) {
            if( in_array($produk->id, $oldStokKey) ){
                StokProduk::where('produk_id', $produk->id)->update([
                    'stok'      => $produk->sisa_stok,
                ]);
            }else{
                StokProduk::create([
                    'produk_id' => $produk->id,
                    'stok'      => $produk->sisa_stok,
                ]);
            }
        }
    }
}
