<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Untuk menghitung total user/admin/petugas
use App\Models\Pelanggan; // Untuk menghitung total pelanggan
use App\Models\Tagihan; // Untuk menghitung total tagihan dan statusnya
use App\Models\Pembayaran; // Untuk menghitung total pembayaran
use App\Models\Level; // Untuk mendapatkan ID level

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total user (admin & petugas)
        $totalUsers = User::count();

        // Hitung total pelanggan
        $totalPelanggan = Pelanggan::count();

        // Hitung total tagihan
        $totalTagihan = Tagihan::count();

        // Hitung tagihan yang belum dibayar
        $tagihanBelumBayar = Tagihan::where('status', 'belum_dibayar')->count();

        // Hitung total pembayaran
        $totalPembayaran = Pembayaran::count();

        // Anda bisa juga mendapatkan data dari level tertentu jika diperlukan
        // Misal: total admin
        // $adminLevelId = Level::where('nama_level', 'Administrator')->value('id_level');
        // $totalAdmin = User::where('id_level', $adminLevelId)->count();
        $dataChart = [
            'sudah_dibayar' => $totalPembayaran,
            'belum_dibayar' => $tagihanBelumBayar,
        ];
        return view('admin.dashboard', compact('totalUsers', 'totalPelanggan', 'totalTagihan', 'tagihanBelumBayar', 'totalPembayaran', 'dataChart'));

        // return view('admin.dashboard', compact('totalUsers', 'totalPelanggan', 'totalTagihan', 'tagihanBelumBayar', 'totalPembayaran'));
    }
}
