<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    // Menampilkan data barang masuk
    public function index()
    {
        // Ambil data barang masuk dan hubungan dengan barang dan supplier
        $barangmasuk = BarangMasuk::with(['barang', 'supplier'])->latest()->paginate(5);
        $barang = Barang::all(); // Ambil semua barang
        $supplier = Supplier::all(); // Ambil semua supplier

        return view('content.barang_masuk.index', compact('barangmasuk', 'barang', 'supplier'));
    }

    // Menyimpan data barang masuk
    public function store(Request $request)
    {
        // Bersihkan harga_beli dari titik ribuan agar valid sebagai angka
        $hargaBeli = (float) str_replace('.', '', $request->harga_beli);

        // Validasi data yang masuk
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'supplier_id' => 'required|exists:supplier,id',
            'tanggal_masuk' => 'required|date',
            'jumlah' => 'required|integer',
            'harga_beli' => 'required',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Membuat kode transaksi berdasarkan format tertentu
        $lastId = BarangMasuk::max('id') ?? 0;
        $kode_transaksi = 'BM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // Menyimpan data barang masuk ke database
        BarangMasuk::create([
            'kode_transaksi' => $kode_transaksi,
            'barang_id' => $request->barang_id,
            'supplier_id' => $request->supplier_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'stok_masuk' => $request->jumlah,
            'harga_beli' => $hargaBeli, // ← Disimpan hasil yang sudah dibersihkan
            'keterangan' => $request->keterangan,
            'user_id' => auth()->user()->id,
            'user' => auth()->user()->name,
        ]);
        // ✅ Tambahkan stok ke tabel barang
        $barang = Barang::findOrFail($request->barang_id);
        $barang->stok += $request->jumlah;
        $barang->harga_beli = $hargaBeli;
        $barang->save();
        // Redirect dengan pesan sukses
        return redirect()->route('admin.barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }
public function update(Request $request, $id)
{
    $barangMasuk = BarangMasuk::findOrFail($id);
    
    $request->validate([
        'barang_id' => 'required|exists:barang,id',
        'supplier_id' => 'required|exists:supplier,id',
        'tanggal_masuk' => 'required|date',
        'jumlah' => 'required|integer',
        'harga_beli' => 'required',
        'keterangan' => 'nullable|string|max:255',
    ]);

    $hargaBeli = (float) str_replace('.', '', $request->harga_beli);

    // Hitung selisih stok
    $selisih = $request->jumlah - $barangMasuk->stok_masuk;

    // Update stok di tabel barang
    $barang = Barang::findOrFail($request->barang_id);
    $barang->stok += $selisih;
    $barang->save();

    // Update data barang masuk
    $barangMasuk->update([
        'barang_id' => $request->barang_id,
        'supplier_id' => $request->supplier_id,
        'tanggal_masuk' => $request->tanggal_masuk,
        'stok_masuk' => $request->jumlah,
        'harga_beli' => $hargaBeli,
        'keterangan' => $request->keterangan,
    ]);

    return redirect()->route('admin.barang-masuk.index')->with('success', 'Data barang masuk berhasil diperbarui.');
}


    // Menghapus data barang masuk
  public function destroy($id)
{
    // Mencari data barang masuk berdasarkan ID
    $barangmasuk = BarangMasuk::findOrFail($id);

    // Ambil barang terkait
    $barang = Barang::find($barangmasuk->barang_id);

    if ($barang) {
        // Kurangi stok barang
        $barang->stok -= $barangmasuk->stok_masuk;

        // Pastikan stok tidak minus
        if ($barang->stok < 0) {
            $barang->stok = 0;
        }

        // Simpan update stok
        $barang->save();
    }

    // Hapus data barang masuk
    $barangmasuk->delete();

    // Redirect dengan pesan sukses
    return redirect()->route('admin.barang-masuk.index')->with('success', 'Barang masuk berhasil dihapus dan stok diperbarui.');
}

}
