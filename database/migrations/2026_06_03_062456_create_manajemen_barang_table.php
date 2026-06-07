<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manajemen_barang', function (Blueprint $table) {

            $table->id();

            $table->string('id_barang')->unique();

            $table->string('nama_barang');

            $table->integer('lead_time')->default(0);

            $table->integer('safety_stock')->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manajemen_barang');
    }
};