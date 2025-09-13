<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;
use Illuminate\Support\Facades\DB;

class LapPenjualanAdminController extends Controller
{
   public function index(Request $request)
{
    $tahun = $request->tahun ?? date('Y');
    $bulan = $request->bulan ?? date('m');

    $query = Penjualan::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
    $penjualan = $query->orderBy('tanggal', 'desc')->get();
    $penjualanIds = $penjualan->pluck('id');

    $totalTransaksi = $penjualan->count();
    $jumlahBarangTerjual = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)->sum('jumlah');

    $totalModal = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)
        ->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
        ->select(DB::raw('SUM(detail_penjualan.jumlah * barang.harga_beli) as total_modal'))
        ->value('total_modal');

    $totalPenjualan = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)
        ->select(DB::raw('SUM(jumlah * harga) as total_penjualan'))
        ->value('total_penjualan');

    $pendapatan = $totalPenjualan - $totalModal;

    return view('content.lap-penjualan-admin.penjualan', compact(
        'penjualan', 'totalTransaksi', 'jumlahBarangTerjual',
        'totalModal', 'totalPenjualan', 'pendapatan', 'bulan', 'tahun'
    ));
}

    

    public function exportExcel(Request $request)
    {
         $today = now()->format('Y-m-d');
        return Excel::download(new LaporanPenjualanExport($request->tahun, $request->bulan), "Laporan-Penjualan{$today}.xlsx");
    }
    public function exportPdf(Request $request)
    {
        $data = $this->getLaporanData($request);
        $pdf = Pdf::loadView('content.lap-penjualan-admin.penjualan-pdf', $data)->setPaper('a4', 'portrait');
        $today = now()->format('Y-m-d');
        return $pdf->download("Laporan-Penjualan{$today}.pdf");
    }

private function getLaporanData(Request $request)
{
    $tahun = (int) ($request->tahun ?? date('Y'));
    $bulan = is_numeric($request->bulan ?? null) ? (int)$request->bulan : null;

    $query = Penjualan::whereYear('tanggal', $tahun);
    if ($bulan) {
        $query->whereMonth('tanggal', $bulan);
    }

    $penjualan = $query->with('detailPenjualan.barang', 'user')->get();

    $penjualanIds = $penjualan->pluck('id');
    $totalTransaksi = $penjualan->count();

    $jumlahBarangTerjual = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)->sum('jumlah');

    $totalModal = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)
        ->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
        ->select(DB::raw('SUM(detail_penjualan.jumlah * barang.harga_beli) as total_modal'))
        ->value('total_modal');

    $totalPenjualan = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)
        ->select(DB::raw('SUM(jumlah * harga) as total_penjualan'))
        ->value('total_penjualan');

    $pendapatan = $totalPenjualan - $totalModal;
    $pendapatan = max($pendapatan, 0);

    return compact(
        'tahun', 'bulan', 'penjualan',
        'totalTransaksi', 'jumlahBarangTerjual',
        'totalModal', 'totalPenjualan', 'pendapatan'
    );
}


}