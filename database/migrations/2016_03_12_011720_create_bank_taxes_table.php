<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_id');
            $table->enum('type', ['debit', 'credit_card'])->default('debit');
            $table->float('tax')->default(0);
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
        Schema::drop('bank_taxes');
    }
}
