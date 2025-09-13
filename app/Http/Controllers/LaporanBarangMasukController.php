<?php
namespace App\Http\Controllers;

use App\Exports\BarangMasukExport;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanBarangMasukController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request, atau default ke bulan dan tahun sekarang
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        // Ambil data barang masuk yang sesuai bulan dan tahun
        $barangMasuk = BarangMasuk::with(['barang', 'supplier'])
            ->whereMonth('tanggal_masuk', $bulan)
            ->whereYear('tanggal_masuk', $tahun)
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('content.laporan-masuk.barang-masuk', compact('barangMasuk', 'bulan', 'tahun'));
    }
    public function exportExcel(Request $request)
    {
         $today = now()->format('Y-m-d');
        return Excel::download(new BarangMasukExport($request->bulan, $request->tahun), "laporan_barang_masuk{$today}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $query = BarangMasuk::with('barang.kategori');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('created_at', $request->bulan)
                ->whereYear('created_at', $request->tahun);
        }

        $barangMasuk = $query->get();

        $pdf = Pdf::loadView('content.laporan-masuk.barang-masuk-pdf', compact('barangMasuk'));
         $today = now()->format('Y-m-d');
        return $pdf->download("laporan_barang_masuk{$today}.pdf");
    }
}