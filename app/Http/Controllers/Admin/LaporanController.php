<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penggunaan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman indeks laporan dengan pilihan jenis laporan.
     */
    public function index()
    {
        return view('admin.laporan.index');
    }

    /**
     * Menampilkan laporan data penggunaan.
     */
    public function laporanPenggunaan(Request $request)
    {
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', 'all');

        $query = Penggunaan::with('pelanggan.tarif');

        if ($bulan !== 'all') {
            $query->where('bulan', (int) $bulan);
        }
        if ($tahun !== 'all') {
            $query->where('tahun', (int) $tahun);
        }

        $laporanPenggunaan = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        $months = [
            'all' => 'Semua Bulan',
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // --- Perbaikan di sini untuk $years ---
        $existingYears = Penggunaan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun'); // Ini masih akan memberikan key numerik

        // Konversi ke format tahun => tahun, dan tambahkan tahun saat ini
        $years = collect();
        foreach ($existingYears as $year) {
            $years->put($year, (string) $year); // Key = Year, Value = Year (string)
        }

        $currentYear = (int) date('Y');
        if (!$years->has($currentYear)) {
            // Check if current year key exists
            $years->put($currentYear, (string) $currentYear);
            $years = $years->sortKeysDesc(); // Sort by year (key) descending
        }

        $years->prepend('Semua Tahun', 'all'); // Tambahkan 'all' di awal

        return view('admin.laporan.penggunaan', compact('laporanPenggunaan', 'bulan', 'tahun', 'months', 'years'));
    }

    public function laporanTagihan(Request $request)
    {
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', 'all');
        $status = $request->input('status', 'all');

        $query = Tagihan::with('pelanggan.tarif');

        if ($bulan !== 'all') {
            $query->where('bulan', (int) $bulan);
        }
        if ($tahun !== 'all') {
            $query->where('tahun', (int) $tahun);
        }
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $laporanTagihan = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        $months = [
            'all' => 'Semua Bulan',
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // --- Perbaikan di sini untuk $years ---
        $existingYears = Tagihan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $years = collect();
        foreach ($existingYears as $year) {
            $years->put($year, (string) $year);
        }

        $currentYear = (int) date('Y');
        if (!$years->has($currentYear)) {
            $years->put($currentYear, (string) $currentYear);
            $years = $years->sortKeysDesc();
        }

        $years->prepend('Semua Tahun', 'all');

        $statuses = [
            'all' => 'Semua Status',
            'belum_dibayar' => 'Belum Dibayar',
            'sudah_dibayar' => 'Sudah Dibayar',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('admin.laporan.tagihan', compact('laporanTagihan', 'bulan', 'tahun', 'status', 'months', 'years', 'statuses'));
    }

    public function laporanPembayaran(Request $request)
    {
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', 'all');

        $query = Pembayaran::with(['tagihan.pelanggan', 'user']);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal_pembayaran', (int) $bulan);
        }
        if ($tahun !== 'all') {
            $query->whereYear('tanggal_pembayaran', (int) $tahun);
        }

        $laporanPembayaran = $query->orderBy('tanggal_pembayaran', 'desc')->get();

        $months = [
            'all' => 'Semua Bulan',
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // --- Perbaikan di sini untuk $years ---
        $existingYears = Pembayaran::selectRaw('YEAR(tanggal_pembayaran) as year_val')->distinct()->orderBy('year_val', 'desc')->pluck('year_val'); // Ini akan memberikan key numerik

        $years = collect();
        foreach ($existingYears as $year) {
            $years->put($year, (string) $year);
        }

        $currentYear = (int) date('Y');
        if (!$years->has($currentYear)) {
            $years->put($currentYear, (string) $currentYear);
            $years = $years->sortKeysDesc();
        }

        $years->prepend('Semua Tahun', 'all');

        return view('admin.laporan.pembayaran', compact('laporanPembayaran', 'bulan', 'tahun', 'months', 'years'));
    }
}
