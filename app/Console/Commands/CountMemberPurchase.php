<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CountMemberPurchase as CountMemberPurchaseJob;

class CountMemberPurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberpurchase:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count Member Purchase';

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
        $this->info('Menghitung jumlah pembelian member...');
        dispatch(new CountMemberPurchaseJob());
    }
}
