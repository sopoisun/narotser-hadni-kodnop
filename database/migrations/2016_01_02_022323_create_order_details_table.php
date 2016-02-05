<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('produk_id');
            $table->integer('hpp');
            $table->integer('harga_jual');
            $table->integer('qty');
            $table->enum('use_mark_up', ['Ya', 'Tidak'])->default('Tidak');
            $table->integer('mark_up');
            $table->string('note');
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
        Schema::drop('order_details');
    }
}
