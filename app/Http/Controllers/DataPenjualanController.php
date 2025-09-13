<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class DataPenjualanController extends Controller
{
     public function index()
    {
        $penjualan = Penjualan::with('user')->latest()->paginate(10);
        return view('content.dataPenjualan.index', compact('penjualan'));
    }

    // Menampilkan detail penjualan
    public function show($id)
    {
        $penjualan = Penjualan::with('user')->findOrFail($id);
        $detail = DetailPenjualan::where('penjualan_id', $id)->get();

        return view('content.dataPenjualan.show', compact('penjualan', 'detail'));
    }

    // Menghapus penjualan dan detailnya
public function destroy($id)
{
    $penjualan = Penjualan::findOrFail($id);

    // Cek apakah user yang login adalah pemilik data
    if ($penjualan->user_id !== auth()->id()) {
        return abort(403, 'Kamu tidak punya akses untuk menghapus data ini.');
    }

    // Jika iya, hapus
    $penjualan->delete();

    return redirect()->route('Datapenjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
}

}
