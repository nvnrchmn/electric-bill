<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LevelController as AdminLevelController; // Import LevelController
use App\Http\Controllers\Admin\UserController as AdminUserController; // Import UserController
use App\Http\Controllers\Admin\TarifController as AdminTarifController; // Import TarifController
use App\Http\Controllers\Admin\PelangganController as AdminPelangganController; // Import PelangganController
use App\Http\Controllers\Admin\PenggunaanController as AdminPenggunaanController; // Import PenggunaanController
use App\Http\Controllers\Admin\TagihanController as AdminTagihanController; // Import TagihanController
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController; // Import PembayaranController
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController; // Import LaporanController

use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PenggunaanController as PetugasPenggunaanController; // Import PenggunaanController untuk Petugas
use App\Http\Controllers\Petugas\TagihanController as PetugasTagihanController;
use App\Http\Controllers\Petugas\PembayaranController as PetugasPembayaranController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\TagihanController as PelangganTagihanController;
use App\Http\Controllers\Pelanggan\PenggunaanController as PelangganPenggunaanController;
use App\Http\Controllers\Pelanggan\PembayaranController as PelangganPembayaranController;
use App\Models\Level;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// After login, redirect to specific dashboard based on level
Route::get('/home', [HomeController::class, 'index'])->middleware('auth');

// Route Group for Admin
// Pastikan Level::ADMINISTRATOR_ID sesuai dengan ID level Administrator di DB Anda
Route::middleware(['auth', 'level:' . Level::ADMINISTRATOR_ID])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Routes untuk Manajemen Level
        Route::resource('levels', AdminLevelController::class);
        // Routes untuk Manajemen User (Admin & Petugas)
        Route::resource('users', AdminUserController::class);
        // Routes untuk Manajemen Tarif
        Route::resource('tarifs', AdminTarifController::class);
        // Routes untuk Manajemen Pelanggan
        Route::resource('pelanggans', AdminPelangganController::class);
        // Routes untuk Manajemen Penggunaan
        Route::resource('penggunaans', AdminPenggunaanController::class);
        // Routes untuk Manajemen Tagihan
        Route::resource('tagihans', AdminTagihanController::class);
        // Rute khusus untuk generate tagihan dari penggunaan
        Route::post('penggunaans/{penggunaan}/generate-tagihan', [AdminTagihanController::class, 'generateTagihan'])->name('penggunaans.generateTagihan');
        // Routes untuk Manajemen Pembayaran
        Route::resource('pembayarans', AdminPembayaranController::class);
        // Rute khusus untuk proses pembayaran tagihan
        Route::get('tagihans/{tagihan}/bayar', [AdminPembayaranController::class, 'createPembayaran'])->name('tagihans.createPembayaran');
        Route::post('tagihans/{tagihan}/bayar', [AdminPembayaranController::class, 'storePembayaran'])->name('tagihans.storePembayaran');
        // Laporan
        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/penggunaan', [AdminLaporanController::class, 'laporanPenggunaan'])->name('laporan.penggunaan');
        Route::get('laporan/tagihan', [AdminLaporanController::class, 'laporanTagihan'])->name('laporan.tagihan');
        Route::get('laporan/pembayaran', [AdminLaporanController::class, 'laporanPembayaran'])->name('laporan.pembayaran');
    });

Route::prefix('petugas')
    ->name('petugas.')
    ->middleware(['auth', 'level:' . Level::PETUGAS_ID])
    ->group(function () {
        Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
        Route::resource('penggunaan', PetugasPenggunaanController::class);
        Route::get('tagihan', [PetugasTagihanController::class, 'index'])->name('tagihan.index'); // Daftar Tagihan
        Route::get('tagihan/{tagihan}', [PetugasTagihanController::class, 'show'])->name('tagihan.show');
        // Rute untuk Pembayaran Petugas (Hanya index, create, store, show)
        Route::get('pembayaran', [PetugasPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('pembayaran/create', [PetugasPembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('pembayaran', [PetugasPembayaranController::class, 'store'])->name('pembayaran.store');
        Route::get('pembayaran/{pembayaran}', [PetugasPembayaranController::class, 'show'])->name('pembayaran.show');
    });

Route::prefix('pelanggan')
    ->name('pelanggan.')
    ->middleware(['auth', 'level:' . Level::PELANGGAN_ID])
    ->group(function () {
        Route::get('dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
        Route::resource('tagihan', PelangganTagihanController::class)->only(['index', 'show']);
        Route::post('tagihan/{id}/bayar', [PelangganTagihanController::class, 'bayar'])->name('tagihan.bayar');
        Route::resource('penggunaan', PelangganPenggunaanController::class)->only(['index', 'show']);
        Route::resource('pembayaran', PelangganPembayaranController::class)->only(['index', 'show']);
    });
require __DIR__ . '/auth.php';
