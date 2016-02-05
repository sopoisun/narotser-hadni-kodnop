<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBayarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_bayars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('karyawan_id');
            $table->integer('diskon');
            $table->integer('bayar');
            $table->enum('type_bayar', ['tunai', 'debit', 'credit_card'])->default('tunai');
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
        Schema::drop('order_bayars');
    }
}
