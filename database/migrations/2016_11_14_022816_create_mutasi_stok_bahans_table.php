<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutasiStokBahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_stok_bahans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bahan_id');
            $table->float('before');
            $table->float('pembelian');
            $table->float('penjualan');
            $table->float('adjustment_increase');
            $table->float('adjustment_reduction');
            $table->float('sisa');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mutasi_stok_bahans');
    }
}
