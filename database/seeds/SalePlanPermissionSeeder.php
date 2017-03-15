<?php

use Illuminate\Database\Seeder;
use App\Permission;

class SalePlanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // main
        Permission::create(['name' => 'saleplan.read', 'display' => 'Lihat Rencana Penjualan']);
        Permission::create(['name' => 'saleplan.create', 'display' => 'Tambah Rencana Penjualan']);
        Permission::create(['name' => 'saleplan.update', 'display' => 'Ubah Rencana Penjualan']);
        Permission::create(['name' => 'saleplan.detail', 'display' => 'Lihat Detail Rencana Penjualan']);
        Permission::create(['name' => 'saleplan.detail.bahan', 'display' => 'Lihat Detail Bahan Rencana Penjualan']);
    }
}
