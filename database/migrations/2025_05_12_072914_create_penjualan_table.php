<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal');
            $table->integer('total');       // Total belanja
            $table->integer('bayar');       // Jumlah yang dibayar
            $table->integer('kembalian');   // Selisih bayar - total
            $table->unsignedBigInteger('kasir_id')->nullable(); // Relasi ke kasir (user)
            $table->timestamps();

            // Relasi ke tabel users (jika ada)
            // $table->foreign('kasir_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
