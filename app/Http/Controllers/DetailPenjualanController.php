<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use Illuminate\Http\Request;

class DetailPenjualanController extends Controller
{
 public function destroy($id)
{
    $detail = DetailPenjualan::findOrFail($id);

    // Optional: tambahkan validasi user hanya bisa hapus datanya sendiri
    if ($detail->penjualan->user_id != auth()->id()) {
        abort(403, 'Tidak diizinkan menghapus item ini');
    }

    $detail->delete();
    return back()->with('success', 'Item berhasil dihapus');
}

}
