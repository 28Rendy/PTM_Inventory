<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardKasirController extends Controller
{
   public function index()
    {
        $today = Carbon::today();

        $jumlahTransaksi = Penjualan::whereDate('tanggal', $today)->count();
        $totalPenjualan = Penjualan::whereDate('tanggal', $today)->sum('total');

        $transaksiTerbaru = Penjualan::orderBy('tanggal', 'desc')->take(5)->get();

        return view('content.kasir.dashboard', compact('jumlahTransaksi', 'totalPenjualan', 'transaksiTerbaru'));
    }
}
