<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nota')->unique()->nullable();
            $table->date('tanggal');
            $table->string('karyawan_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->enum('state', ['On Going', 'Merged', 'Closed', 'Canceled'])->default('On Going');
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
        Schema::drop('orders');
    }
}
