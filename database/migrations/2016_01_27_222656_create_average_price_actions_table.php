<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAveragePriceActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('average_price_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['bahan', 'produk']);
            $table->string('relation_id');
            $table->integer('old_price');
            $table->integer('input_price');
            $table->integer('average_with_round'); // average dengan pembulatan
            $table->string('action');
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
        Schema::drop('average_price_actions');
    }
}
