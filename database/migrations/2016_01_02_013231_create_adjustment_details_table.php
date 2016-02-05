<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adjustment_id');
            $table->enum('type', ['bahan', 'produk']);
            $table->enum('state', ['reduction', 'increase']);
            $table->string('relation_id');
            $table->integer('harga');
            $table->integer('qty');
            $table->text('keterangan');
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
        Schema::drop('adjustment_details');
    }
}
