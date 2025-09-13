<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';  // Nama tabel yang sesuai di database

    // Tentukan kolom-kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'kode_transaksi',
        'barang_id',
        'stok_masuk',
        'harga_beli',
        'tanggal_masuk',
        'supplier_id',
        'user_id',
        'keterangan'
    ];

    // Definisikan relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Definisikan relasi ke model Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Definisikan relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
