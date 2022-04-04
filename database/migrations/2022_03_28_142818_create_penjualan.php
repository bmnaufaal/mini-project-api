<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('id_nota', 30)->primary()->unique();
            $table->date('tgl');
            $table->string('kode_pelanggan');
            $table->foreign('kode_pelanggan')->references('id_pelanggan')->on('pelanggan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('subtotal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
