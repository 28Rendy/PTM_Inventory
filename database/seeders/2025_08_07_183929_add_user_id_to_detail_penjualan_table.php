<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //     public function up()
    // {
    //     Schema::table('detail_penjualan', function (Blueprint $table) {
    //         // Karena kolom sudah ada, cukup tambahkan foreign key saja
    //         $table->foreign('user_id')
    //               ->references('id')
    //               ->on('users')
    //               ->onDelete('cascade');
    //     });
    // }

    //     /**
    //      * Reverse the migrations.
    //      */
    //     public function down(): void
    //     {
    //         Schema::table('detail_penjualan', function (Blueprint $table) {
    //             //
    //         });
    //     }



    //     public function up()
    // {
    //     Schema::table('detail_penjualan', function (Blueprint $table) {
    //         // Tambah kolom user_id
    //         $table->unsignedBigInteger('user_id')->nullable();

    //         // Tambah foreign key ke tabel users
    //         $table->foreign('user_id')
    //               ->references('id')
    //               ->on('users')
    //               ->onDelete('cascade');
    //     });
    // }

    // public function down(): void
    // {
    //     Schema::table('detail_penjualan', function (Blueprint $table) {
    //         $table->dropForeign(['user_id']);
    //         $table->dropColumn('user_id');
    //     });





    public function up()
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // Tambah kolom user_id terlebih dahulu
            if (!Schema::hasColumn('detail_penjualan', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            // Baru tambahkan foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    
    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
