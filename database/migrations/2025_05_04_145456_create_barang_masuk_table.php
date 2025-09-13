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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->unsignedBigInteger('id_kategori');
            $table->integer('stok_masuk');
            $table->decimal('harga_beli', 15, 2);
            $table->timestamp('tanggal_masuk')->useCurrent();
            $table->unsignedBigInteger('id_supplier');
            $table->unsignedBigInteger('user_id'); // 🔹 kolom baru relasi ke users
            $table->timestamps();
            
            // Menambahkan foreign key
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');
            $table->foreign('id_supplier')->references('id')->on('supplier')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // 🔹 relasi ke users
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
