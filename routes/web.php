<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardKasirController;
use App\Http\Controllers\DashboardPimpinanController;
use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LapBarangAdminController;
use App\Http\Controllers\LapMasukAdminController;
use App\Http\Controllers\LaporanBarangMasukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LapPenjualanAdminController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\riwayatPenjualanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/',[HomeController::class, 'index']);
Route::get('/', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'verify'])->name('auth.verify');

Route::group(['middleware' => ['auth:admin,kasir,pimpinan']], function () {
    Route::get('/profile', [ProfilController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfilController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profile.update');
    Route::get('/password/change', [ProfilController::class, 'changePassword'])->name('password.change');
    Route::post('/password/update', [ProfilController::class, 'updatePassword'])->name('password.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin/home', [DashboardAdminController::class, 'index'])->name('admin.dashboard.index');

    Route::get('admin/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::post('/admin/user/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::get('admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
    Route::post('/admin/kategori/store', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::get('/admin/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::post('/admin/kategori/update/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    Route::get('admin/supplier', [SupplierController::class, 'index'])->name('admin.supplier.index');
    Route::post('/admin/supplier/store', [SupplierController::class, 'store'])->name('admin.supplier.store');
    Route::get('/admin/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('admin.supplier.edit');
    Route::post('/admin/supplier/update/{id}', [SupplierController::class, 'update'])->name('admin.supplier.update');
    Route::delete('/admin/supplier/{id}', [SupplierController::class, 'destroy'])->name('admin.supplier.destroy');

    Route::get('admin/pengeluaran', [PengeluaranController::class, 'index'])->name('admin.pengeluaran.index');
    Route::post('/admin/pengeluaran/store', [PengeluaranController::class, 'store'])->name('admin.pengeluaran.store');
    Route::get('/admin/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->name('admin.pengeluaran.edit');
    Route::post('/admin/pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->name('admin.pengeluaran.update');
    Route::delete('/admin/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('admin.pengeluaran.destroy');

    Route::get('admin/barang', [BarangController::class, 'index'])->name('admin.barang.index');
    Route::post('/admin/barang/store', [BarangController::class, 'store'])->name('admin.barang.store');
    Route::get('/admin/barang/edit/{id}', [BarangController::class, 'edit'])->name('admin.barang.edit');
    Route::put('/admin/barang/update/{id}', [BarangController::class, 'update'])->name('admin.barang.update');
    Route::delete('/admin/barang/{id}', [BarangController::class, 'destroy'])->name('admin.barang.destroy');

    Route::get('admin/barang-masuk', [BarangMasukController::class, 'index'])->name('admin.barang-masuk.index');
    Route::post('/admin/barang-masuk/store', [BarangMasukController::class, 'store'])->name('admin.barang-masuk.store');
    Route::get('/admin/barang-masuk/edit/{id}', [BarangMasukController::class, 'edit'])->name('admin.barang-masuk.edit');
    Route::put('/admin/barang-masuk/update/{id}', [BarangMasukController::class, 'update'])->name('admin.barang-masuk.update');
    Route::delete('/admin/barang-masuk/{id}', [BarangMasukController::class, 'destroy'])->name('admin.barang-masuk.destroy');
    Route::put('/barang-masuk/{id}', [BarangMasukController::class, 'update'])->name('admin.barang-masuk.update');


    Route::get('/data-penjualan', [DataPenjualanController::class, 'index'])->name('Datapenjualan.index');
    Route::get('/data-penjualan/{id}', [DataPenjualanController::class, 'show'])->name('Datapenjualan.show');
    Route::delete('/data-penjualan/{id}', [DataPenjualanController::class, 'destroy'])->name('Datapenjualan.destroy');
    //Route::delete('/detail-penjualan/{id}', [DetailPenjualanController::class, 'destroy'])->name('detailpenjualan.destroy');

    Route::get('/laporan-barang', [LapBarangAdminController::class, 'index'])->name('laporan.barang');
    Route::get('/laporan-barang/export-excel', [LapBarangAdminController::class, 'exportExcel'])->name('laporan.barang.excel');
    Route::get('/laporan-barang/export-pdf', action: [LapBarangAdminController::class, 'exportPdf'])->name('laporan.barang.pdf');

    Route::get('/laporan-masuk', [LapMasukAdminController::class, 'index'])->name('laporan.masuk');
    Route::get('/laporan-masuk/export-excel', [LapMasukAdminController::class, 'exportExcel'])->name('laporan.masuk.excel');
    Route::get('/laporan-masuk/export-pdf', [LapMasukAdminController::class, 'exportPdf'])->name('laporan.masuk.pdf');


    Route::get('/laporan-penjualan-admin', [LapPenjualanAdminController::class, 'index'])->name('laporan.penjualan.admin');
    Route::get('/laporan-penjualan-admin/pdf', [LapPenjualanAdminController::class, 'exportPdf'])->name('laporan.penjualan.admin.pdf');
    Route::get('/laporan-penjualan-admin/excel', [LapPenjualanAdminController::class, 'exportExcel'])->name('laporan.penjualan.admin.excel');

});

Route::group(['middleware' => 'auth:kasir'], function () {
    Route::get('/kasir/home', [DashboardKasirController::class, 'index'])->name('kasir.dashboard.index');

    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('/penjualan/add', [PenjualanController::class, 'addToCart'])->name('penjualan.addToCart');
    Route::delete('/penjualan/remove/{index}', [PenjualanController::class, 'removeItem'])->name('penjualan.remove');
    Route::post('/penjualan/bayar', [PenjualanController::class, 'prosesPembayaran'])->name('penjualan.prosesPembayaran');
    Route::post('/penjualan/simpan', [PenjualanController::class, 'simpan'])->name('penjualan.simpan');
    Route::get('/penjualan/cetak/{id}', [PenjualanController::class, 'cetak'])->name('penjualan.cetak');
    Route::get('/penjualan/struk-html/{id}', [PenjualanController::class, 'strukHtml'])->name('penjualan.struk.html');
    Route::get('/penjualan/reset', [PenjualanController::class, 'reset'])->name('penjualan.reset');
    Route::get('/penjualan/cari-barang', [PenjualanController::class, 'cariBarang'])->name('penjualan.cariBarang');



    Route::get('/Riwayatpenjualan', [riwayatPenjualanController::class, 'index'])->name('Riwayatpenjualan.index');
    Route::get('/Riwayatpenjualan/{id}', [riwayatPenjualanController::class, 'show'])->name('Riwayatpenjualan.show');
   // Route::delete('/detail-penjualan/{id}', [DetailPenjualanController::class, 'destroy'])->name('detail-penjualan.destroy');
    Route::delete('/Riwayatpenjualan/{id}', [riwayatPenjualanController::class, 'destroy'])->name('Riwayatpenjualan.destroy');
});

Route::group(['middleware' => 'auth:pimpinan'], function () {
    Route::get('/pimpinan/home', [DashboardPimpinanController::class, 'index'])->name('pimpinan.dashboard.index');

    Route::get('/laporan-stok', [LaporanController::class, 'index'])->name('laporan.stok');
    Route::get('/laporan-stok/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.stok.excel');
    Route::get('/laporan-stok/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.stok.pdf');

    Route::get('/laporan-barang-masuk', [LaporanBarangMasukController::class, 'index'])->name('laporan.barang-masuk');
    Route::get('/laporan-barang-masuk/export-excel', [LaporanBarangMasukController::class, 'exportExcel'])->name('laporan.barang-masuk.excel');
    Route::get('/laporan-barang-masuk/export-pdf', [LaporanBarangMasukController::class, 'exportPdf'])->name('laporan.barang-masuk.pdf');


    Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('/laporan-penjualan/pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.pdf');
    Route::get('/laporan-penjualan/excel', [LaporanPenjualanController::class, 'exportExcel'])->name('laporan.penjualan.excel');

});


Route::group(['middleware' => 'auth:pimpinan,admin'], function () {

});





