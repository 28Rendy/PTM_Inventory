<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;


class PenjualanController extends Controller
{
    public function index()
    {

        return view('content.penjualan.index');
    }

   public function addToCart(Request $request)
{
    // Ambil data barang dari DB berdasarkan kode atau nama
    $barang = Barang::where('kode_barang', $request->kode_barang)
        ->orWhere('nama_barang', 'LIKE', '%' . $request->kode_barang . '%')
        ->first();

    if (!$barang) {
        \Log::info('Barang tidak ditemukan.');
        return redirect()->route('penjualan.index')->with('error', 'Barang tidak ditemukan.');
    }

    // Jika nama_barang atau harga tidak dikirim dari form, isi manual
    if (!$request->nama_barang || !$request->harga) {
        $request->merge([
            'nama_barang' => $barang->nama_barang,
            'harga' => $barang->harga_jual
        ]);
    }

    // Tambah ke keranjang
    $cart = session()->get('cart', []);
    $found = false;

    foreach ($cart as &$item) {
        if ($item['barang_id'] == $barang->id) {
            $item['jumlah'] += $request->jumlah;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $cart[] = [
            'barang_id' => $barang->id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->route('penjualan.index')->with('success', 'Barang berhasil ditambahkan ke daftar belanja.');
}



    // Menghapus item dari keranjang
    public function removeItem($index)
    {
        // Ambil cart dari session
        $cart = session()->get('cart', []);

        // Cek apakah ada item yang ingin dihapus
        if (isset($cart[$index])) {
            // Hapus item dari keranjang
            unset($cart[$index]);

            // Simpan ulang ke session
            session()->put('cart', $cart);

            // Kirimkan pesan sukses
            return redirect()->route('penjualan.index')->with('success', 'Barang berhasil dihapus.');
        }

        // Jika item tidak ditemukan, beri pesan error
        return redirect()->route('penjualan.index')->with('error', 'Barang tidak ditemukan.');
    }

    // Mencari barang berdasarkan kode barang (Ajax)
    public function cariBarang(Request $request)
    {
        $keyword = $request->kode;

        if (!$keyword) {
            return response()->json(['error' => 'Tidak ada kata kunci.']);
        }

        $barang = Barang::where('kode_barang', $keyword)
            ->orWhere('nama_barang', 'LIKE', '%' . $keyword . '%')
            ->first();

        if ($barang) {
            return response()->json([
                'nama_barang' => $barang->nama_barang,
                'harga' => $barang->harga_jual,
            ]);
        }

        return response()->json(['error' => 'Barang tidak ditemukan.']);
    }


    // Proses pembayaran
    // public function prosesPembayaran(Request $request)
    // {

    //     $cart = session()->get('cart', []);
    //     if (count($cart) == 0) {
    //         return redirect()->route('penjualan.index')->with('error', 'Keranjang kosong');
    //     }

    //     $total = 0;
    //     foreach ($cart as $item) {
    //         $total += $item['harga'] * $item['jumlah'];
    //     }

    //     // Validasi bayar
    //     $request->validate([
    //         'bayar' => 'required|numeric|min:' . $total,
    //     ]);

    //     // Mengubah format bayar yang dipisahkan titik (misal: 30.000) menjadi angka
    //     $bayar = str_replace('.', '', $request->bayar);
    //     $bayar = (int) $bayar;

    //     DB::beginTransaction();
    //     try {
    //         // Menyimpan data penjualan
    //         $penjualan = Penjualan::create([
    //             'tanggal' => now(),
    //             'total' => $total,
    //             'bayar' => $bayar,
    //             'kembalian' => $bayar - $total,
    //             'kasir_id' => auth()->id(),
    //         ]);

    //         // Menyimpan detail penjualan dan update stok barang
    //         foreach ($cart as $item) {
    //             DetailPenjualan::create([
    //                 'penjualan_id' => $penjualan->id,
    //                 'barang_id' => $item['barang_id'],
    //                 'nama_barang' => $item['nama_barang'],
    //                 'harga' => $item['harga'],
    //                 'jumlah' => $item['jumlah'],
    //                 'subtotal' => $item['harga'] * $item['jumlah'],
    //             ]);

    //             // Update stok barang
    //             $barang = Barang::find($item['barang_id']);
    //             $barang->stok -= $item['jumlah'];
    //             $barang->save();
    //         }

    //         // Menghapus cart setelah transaksi berhasil
    //         session()->forget('cart');

    //         DB::commit();
    //         return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil!');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return redirect()->back()->with('error', 'Transaksi gagal: ' . $e->getMessage());
    //     }
    // }

    public function cetak($id)
    {
        $penjualan = Penjualan::with('detailPenjualan')->findOrFail($id);

        $pdf = PDF::loadView('content.penjualan.struk', compact('penjualan'))->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('struk_penjualan.pdf');
    }

    public function simpan(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        // Hitung total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // Validasi input bayar
        $bayarInput = $request->bayar;

        if (!$bayarInput) {
            return redirect()->back()->with('error', 'Nominal bayar harus diisi!');
        }

        // Format bayar: hilangkan titik
        $bayar = (int) str_replace('.', '', $bayarInput);

        if ($bayar < $total) {
            return redirect()->back()->with('error', 'Uang yang dibayarkan kurang dari total.');
        }

        $kembalian = $bayar - $total;

        // Simpan ke tabel penjualan
        $penjualan = Penjualan::create([
            'kode_penjualan' => 'PNJ-' . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT),
            'tanggal' => now(),
            'total' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
            'user_id' => auth()->id(),
        ]);

        // Simpan detail barang
        foreach ($cart as $item) {
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'barang_id' => $item['barang_id'],
                'nama_barang' => $item['nama_barang'],
                'harga' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['harga'] * $item['jumlah'],
                'user_id' => auth()->id(),
            ]);
            $barang = Barang::find($item['barang_id']);
            $barang->stok -= $item['jumlah'];
            $barang->save();
        }
        $htmlStruk = view('content.penjualan._struk', compact('penjualan'))->render();
        session([
            'struk' => $htmlStruk,
            'last_penjualan_id' => $penjualan->id,
            'cart' => [] // kosongkan keranjang
        ]);

        // Simpan ID terakhir untuk tombol cetak
        session()->put('last_penjualan_id', $penjualan->id);
        session()->forget('cart'); // kosongkan keranjang

        return back()->with('success', 'Transaksi berhasil disimpan.');
    }

    public function reset()
    {
        session()->forget('cart');
        session()->forget('last_penjualan_id');

        return back()->with('success', 'Transaksi telah direset.');
    }
    public function strukHtml($id)
    {

        $penjualan = Penjualan::with('detailPenjualan')->findOrFail($id);
        return view('content.penjualan.struk-html', compact('penjualan'));
    }



}
