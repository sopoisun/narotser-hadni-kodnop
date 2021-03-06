<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CountBahanQty;

class CountStokBahan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bahan:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung stok bahan';

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
        dispatch(new CountBahanQty());
    }
}
