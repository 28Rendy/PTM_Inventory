<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Penjualan;
use App\Models\Supplier;
use App\Models\DetailPenjualan; // Tambahkan model ini
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Total Barang
        $totalBarang = Barang::count();

        // Total Barang Masuk
        $totalBarangMasuk = BarangMasuk::count();

        // Total Supplier
        $totalSupplier = Supplier::count();

        // Total Penjualan
        $totalPenjualan = Penjualan::count();

        // Transaksi terbaru (ambil 5 terbaru berdasarkan tanggal)
        $transaksiTerbaru = Penjualan::orderBy('tanggal', 'desc')->take(5)->get();

        // Stok Barang kurang dari 5
        $stokKurang = Barang::where('stok', '<', 5)->get();

        // Data Grafik Penjualan Bulanan (berdasarkan kolom 'tanggal')
        $currentYear = date('Y');
        $penjualanPerBulan = Penjualan::selectRaw('MONTH(tanggal) as bulan, SUM(total) as total_penjualan')
            ->whereYear('tanggal', $currentYear)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Siapkan array 12 bulan default 0
        $dataGrafik = array_fill(1, 12, 0);
        foreach ($penjualanPerBulan as $row) {
            $dataGrafik[(int)$row->bulan] = $row->total_penjualan;
        }

        // ✅ Tambahan: Barang Terlaris (Top 5)
        $barangTerlaris = DetailPenjualan::select('barang_id', 'nama_barang')
            ->selectRaw('SUM(jumlah) as total_terjual')
            ->groupBy('barang_id', 'nama_barang')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        return view('content.admin.dashboard', compact(
            'totalBarang',
            'totalBarangMasuk',
            'totalSupplier',
            'totalPenjualan',
            'transaksiTerbaru',
            'stokKurang',
            'dataGrafik',
            'barangTerlaris' // ← ditambahkan ke compact
        ));
    }
}
