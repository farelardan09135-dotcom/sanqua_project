<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SparepartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\RiwayatStokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\LaporanPembelianController; 
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\StockOpnameController;  

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return match (true) {
        auth()->user()->isOwner() => redirect()->route('owner.dashboard'),
        auth()->user()->isAdmin() => redirect()->route('admin.dashboard'),
        default => redirect()->route('kasir.index'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/penjualan', [LaporanPenjualanController::class, 'index'])->name('owner.penjualan');
    Route::get('/penjualan/export', [LaporanPenjualanController::class, 'export'])->name('owner.penjualan.export');

    Route::get('/stok', [LaporanStokController::class, 'index'])->name('owner.stok'); 
    Route::get('/pembelian', [LaporanPembelianController::class, 'index'])->name('owner.pembelian');   
    Route::get('/forecast', [ForecastController::class, 'index'])->name('owner.forecast');
});
// Semua route admin diproteksi: harus login DAN role = admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/inventory', [SparepartController::class, 'index'])->name('admin.inventory');
    Route::post('/inventory', [SparepartController::class, 'store'])->name('admin.inventory.store');
    Route::put('/inventory/{sparepart}', [SparepartController::class, 'update'])->name('admin.inventory.update');
    Route::delete('/inventory/{sparepart}', [SparepartController::class, 'destroy'])->name('admin.inventory.destroy');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('admin.supplier');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('admin.supplier.store');
    Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('admin.supplier.update');
    Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('admin.supplier.destroy');

    Route::get('/customer', [CustomerController::class, 'index'])->name('admin.customer');
    Route::post('/customer', [CustomerController::class, 'store'])->name('admin.customer.store');
    Route::put('/customer/{customer}', [CustomerController::class, 'update'])->name('admin.customer.update');
    Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('admin.customer.destroy');

    Route::get('/user', [UserController::class, 'index'])->name('admin.user');
    Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::patch('/user/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.user.toggle-status');

    Route::get('/pembelian', [PembelianController::class, 'index'])->name('admin.pembelian');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('admin.pembelian.create');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('admin.pembelian.store');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('admin.pembelian.show');

    Route::get('/riwayat-stok', [RiwayatStokController::class, 'index'])->name('admin.riwayat-stok');

    Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('admin.stock-opname');
    Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('admin.stock-opname.store');
    
    Route::get('/setting', fn () => view('admin.setting'))->name('admin.setting');
});

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/detail', [KasirController::class, 'detail'])->name('kasir.detail');
    Route::post('/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar');
   
    Route::get('/nota/{transaction:no_transaksi}', [KasirController::class, 'nota'])->name('kasir.nota');
    Route::post('/nota/{transaction:no_transaksi}/kirim-wa', [KasirController::class, 'kirimNota'])->name('kasir.nota.kirim-wa');

    Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');       // ganti dari closure
    Route::get('/cek-barang', [KasirController::class, 'cekBarang'])->name('kasir.cek-barang'); // ganti dari closure

    Route::get('/setting', fn () => view('kasir.setting'))->name('kasir.setting');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/password', function () {
        return view('profile.password');
    })->name('profile.password'); 
});

require __DIR__.'/auth.php';