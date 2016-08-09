<?php

use Illuminate\Database\Seeder;
use App\Permission;

class AutoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'autodata.date', 'display' => 'Manipulasi A.D. Pertanggal']);
        Permission::create(['name' => 'autodata.date-range', 'display' => 'Manipulasi A.D. Perperiode']);
        Permission::create(['name' => 'autodata.stok', 'display' => 'Manipulasi Stok']);
    }
}
