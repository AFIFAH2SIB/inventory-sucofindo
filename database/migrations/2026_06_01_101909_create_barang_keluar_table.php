<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {

            $table->id();

            $table->string('nomor_pengeluaran');

            $table->string('unit');

            $table->string('id_barang', 50);

            $table->string('nama_barang');

            $table->date('tanggal_keluar');

            $table->integer('jumlah')->unsigned();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};