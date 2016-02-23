<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('satuan');
            $table->enum('konsinyasi', ['Ya', 'Tidak']);
            $table->string('supplier_id')->nullable();
            $table->integer('hpp')->default(0);
            $table->integer('harga')->default(0);
            $table->enum('use_mark_up', ['Ya', 'Tidak']);
            $table->integer('mark_up')->default(0);
            $table->string('produk_kategori_id');
            $table->integer('qty_warning')->default(0);
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
        Schema::drop('produks');
    }
}
