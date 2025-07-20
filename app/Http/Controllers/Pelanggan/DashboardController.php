<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelangganIds = $user->pelanggans->pluck('id_pelanggan');

        // Tagihan Lunas & Belum Lunas
        $totalLunas = \App\Models\Tagihan::whereIn('id_pelanggan', $pelangganIds)->where('status', 'sudah_dibayar')->count();

        $totalBelumLunas = \App\Models\Tagihan::whereIn('id_pelanggan', $pelangganIds)->where('status', 'belum_dibayar')->count();

        // Total KWH Bulan Ini
        $now = Carbon::now();
        $totalKwhBulanIni = \App\Models\Penggunaan::whereIn('id_pelanggan', $pelangganIds)->where('bulan', $now->format('m'))->where('tahun', $now->year)->sum(\DB::raw('meter_akhir - meter_awal'));

        // Total Pembayaran Tahun Ini
        $totalPembayaranTahunIni = \App\Models\Pembayaran::whereIn('id_pelanggan', $pelangganIds)->whereYear('created_at', $now->year)->sum('total_bayar');

        return view('pelanggan.dashboard', compact('user', 'totalLunas', 'totalBelumLunas', 'totalKwhBulanIni', 'totalPembayaranTahunIni'));
    }
}
