<?php

namespace App\Http\Controllers;

use App\Exports\StokBarangExport;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{

    public function index()
    {
        // Ambil semua data barang yang tersedia di gudang
        $barang = Barang::with('kategori')->get();

        return view('content.laporan.stok-barang', compact('barang'));
    }

    public function exportExcel()
    {
         $today = now()->format('Y-m-d');
        return Excel::download(new StokBarangExport(), "laporan_stok_barang{$today}.xlsx");
    }

    public function exportPdf()
    {
        $barang = Barang::with('kategori')->get();

        $pdf = Pdf::loadView('content.laporan.stok-barang-pdf', compact('barang'));
         $today = now()->format('Y-m-d');
        return $pdf->download("laporan_stok_barang{$today}.pdf");
    }
}
