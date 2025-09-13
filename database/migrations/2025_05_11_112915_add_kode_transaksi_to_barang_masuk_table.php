<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('barang_masuk', function (Blueprint $table) {
        // Menambahkan kolom kode_transaksi
        $table->string('kode_transaksi')->unique()->after('id');  // Anda bisa menyesuaikan posisi dengan kolom yang ada
    });
}

public function down()
{
    Schema::table('barang_masuk', function (Blueprint $table) {
        $table->dropColumn('kode_transaksi');
    });
}

};
