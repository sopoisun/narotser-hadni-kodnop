<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalePlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_plan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sale_plan_id');
            $table->string('produk_id');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('sold')->default(0);
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
        Schema::drop('sale_plan_details');
    }
}
