<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJamColumnToOrderBayar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_bayars', function (Blueprint $table) {
            $table->time('jam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_bayars', function (Blueprint $table) {
            $table->dropColumn('jam');
        });
    }
}
