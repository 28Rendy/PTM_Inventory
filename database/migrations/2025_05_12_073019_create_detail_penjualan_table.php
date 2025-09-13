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
        
       Schema::create('detail_penjualan', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('penjualan_id');   // Relasi ke tabel penjualan
    $table->unsignedBigInteger('barang_id');      // Relasi ke tabel barang
    $table->string('nama_barang');                // Nama barang
    $table->integer('harga');                     // Harga barang saat transaksi
    $table->integer('jumlah');                    // Jumlah barang yang dibeli
    $table->integer('subtotal');                  // subtotal (harga * jumlah)
    $table->timestamps();

    // Relasi ke tabel penjualan
    $table->foreign('penjualan_id')->references('id')->on('penjualan')->onDelete('cascade');
    // Relasi ke tabel barang
    $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
