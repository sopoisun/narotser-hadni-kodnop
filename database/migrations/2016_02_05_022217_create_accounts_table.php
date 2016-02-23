<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_akun');
            $table->enum('data_state', ['auto', 'input'])->default('input');
            $table->enum('type', ['debet', 'kredit'])->default('debet');
            $table->string('relation')->nullable();
            $table->enum('can_edit', ['Ya', 'Tidak'])->default('Ya');
            $table->enum('active', [1, 0])->default(1);
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
        Schema::drop('accounts');
    }
}
