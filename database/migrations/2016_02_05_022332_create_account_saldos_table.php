<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_saldos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->string('account_id');
            $table->enum('type', ['debet', 'kredit'])->default('debet');
            $table->integer('nominal');
            $table->string('relation_id')->nullable();
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
        Schema::drop('account_saldos');
    }
}
