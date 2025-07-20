<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penggunaan; // Import model Penggunaan
use App\Models\Pembayaran; // Import model Pembayaran
use App\Models\Tagihan; // Import model Tagihan
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Dapatkan data user yang sedang login

        // 1. Penggunaan Dicatat Bulan Ini oleh Petugas yang Login
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $penggunaanBulanIni = Penggunaan::where('petugas_id', $user->id_user) // Filter berdasarkan petugas yang login
            ->where('bulan', (string) $currentMonth) // Bulan di Penggunaan adalah string
            ->where('tahun', (string) $currentYear) // Tahun di Penggunaan adalah string
            ->count();

        // 2. Pembayaran Diverifikasi Bulan Ini oleh Petugas yang Login
        $pembayaranBulanIni = Pembayaran::where('id_user', $user->id_user) // Filter berdasarkan petugas yang login
            ->whereMonth('tanggal_pembayaran', $currentMonth)
            ->whereYear('tanggal_pembayaran', $currentYear)
            ->count();

        // 3. Total Tagihan Belum Dibayar (Keseluruhan, tidak hanya milik petugas ini)
        $totalTagihanBelumDibayar = Tagihan::where('status', 'belum_dibayar')->count();

        return view('petugas.dashboard', compact('user', 'penggunaanBulanIni', 'pembayaranBulanIni', 'totalTagihanBelumDibayar'));
    }
}
