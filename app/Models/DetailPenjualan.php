<?php

namespace App\Models;

use App\Http\Controllers\PenjualanController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_penjualan'; 

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'nama_barang',
        'harga',
        'jumlah',
        'subtotal',
        'user_id'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
