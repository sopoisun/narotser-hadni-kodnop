<?php

use Illuminate\Database\Seeder;
use App\Permission;

class QtyWarningReport extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'bahan.qty_warning', 'display' => 'Report Qty Warning']);
        Permission::create(['name' => 'produk.qty_warning', 'display' => 'Report Qty Warning']);
    }
}
