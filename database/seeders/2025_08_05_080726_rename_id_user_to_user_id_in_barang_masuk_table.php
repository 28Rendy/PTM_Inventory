<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::table('barang_masuk', function (Blueprint $table) {
    //           $table->renameColumn('id_user','user_id');
    //     });
    // }
public function up(): void
{
    Schema::table('barang_masuk', function (Blueprint $table) {
        // Hapus index unik lama biar gak bentrok
        $table->dropUnique('barang_masuk_kode_transaksi_unique');

        // Rename kolom
        $table->renameColumn('id_user','user_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
              $table->renameColumn('user_id','id_user');
        });
    }
};
