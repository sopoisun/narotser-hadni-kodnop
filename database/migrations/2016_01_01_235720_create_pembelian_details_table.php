<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelianDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pembelian_id');
            $table->string('relation_id');
            $table->integer('qty');
            $table->string('satuan');
            $table->integer('harga');
            $table->integer('stok');
            $table->enum('type', ['bahan', 'produk']);
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
        Schema::drop('pembelian_details');
    }
}
