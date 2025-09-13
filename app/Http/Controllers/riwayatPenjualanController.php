<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use DB;
use Illuminate\Http\Request;

class riwayatPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $penjualan = Penjualan::with('user')->latest()->paginate(10);
        return view('content.riwayatPenjualan.index', compact('penjualan'));
    }

    // Menampilkan detail penjualan
    public function show($id)
    {
        $penjualan = Penjualan::with('user')->findOrFail($id);
        $detail = DetailPenjualan::where('penjualan_id', $id)->get();

        return view('content.riwayatPenjualan.show', compact('penjualan', 'detail'));
    }

    // Menghapus penjualan dan detailnya
 public function destroy($id)
{
    $detail = DetailPenjualan::findOrFail($id);

    // Validasi: hanya user yang input yang bisa hapus
    if ($detail->penjualan->user_id != auth()->id()) {
        abort(403, 'Tidak diizinkan menghapus item ini');
    }

    $penjualan = $detail->penjualan;

    // Hapus item
    $detail->delete();

    // Hitung ulang total & kembalian
    $totalBaru = $penjualan->detailPenjualan()->sum(DB::raw('jumlah * harga'));
    $kembalianBaru = $penjualan->bayar - $totalBaru;

    // Jika semua item dihapus, hapus juga transaksi penjualan
    if ($penjualan->detailPenjualan()->count() == 0) {
        $penjualan->delete();
        return redirect()->route('Datapenjualan.index')->with('success', 'Transaksi dihapus karena tidak ada item');
    } else {
        // Update total dan kembalian
        $penjualan->update([
            'total' => $totalBaru,
            'kembalian' => $kembalianBaru,
        ]);
        return back()->with('success', 'Item berhasil dihapus & total diperbarui');
    }
}



}