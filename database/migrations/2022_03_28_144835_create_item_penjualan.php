<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_penjualan', function (Blueprint $table) {
            $table->string('nota');
            $table->string('kode_barang');
            $table->foreign('nota')->references('id_nota')->on('penjualan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kode_barang')->references('kode')->on('barang')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_penjualan');
    }
}
