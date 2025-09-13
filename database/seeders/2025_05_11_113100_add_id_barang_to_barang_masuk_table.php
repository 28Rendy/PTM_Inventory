<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdBarangToBarangMasukTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            // Menambahkan kolom id_barang sebagai foreign key
            $table->unsignedBigInteger('id_barang')->after('kode_transaksi');
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            // Menghapus kolom id_barang jika migrasi di-rollback
            $table->dropForeign(['id_barang']);
            $table->dropColumn('id_barang');
        });
    }
}
