<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan; // Import model Tagihan
use App\Models\Pelanggan; // Untuk filter/dropdown (opsional)
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Untuk format tanggal/bulan

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar tagihan.
     * Petugas mungkin hanya bisa melihat tagihan dari pelanggan yang mereka tangani,
     * atau semua tagihan, tergantung kebijakan. Untuk awal, kita tampilkan semua tagihan.
     * Filter bulan/tahun bisa ditambahkan.
     */
    public function index(Request $request)
    {
        $query = Tagihan::with('pelanggan', 'penggunaan') // Load relasi pelanggan dan penggunaan
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan bulan dan tahun
        $bulanFilter = $request->input('bulan', 'all');
        $tahunFilter = $request->input('tahun', 'all');

        if ($bulanFilter !== 'all') {
            $query->where('bulan', (int) $bulanFilter);
        }
        if ($tahunFilter !== 'all') {
            $query->where('tahun', (int) $tahunFilter);
        }

        $tagihans = $query->paginate(10); // Paginate hasilnya

        // Data untuk dropdown filter bulan/tahun
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

        $years = Tagihan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $currentYear = (int) date('Y');
        if (!$years->contains($currentYear)) {
            $years->push($currentYear);
            $years = $years->sortDesc();
        }
        $years = $years->mapWithKeys(fn($year) => [$year => $year])->prepend('Semua Tahun', 'all');

        return view('petugas.tagihan.index', compact('tagihans', 'months', 'years', 'bulanFilter', 'tahunFilter'));
    }

    /**
     * Menampilkan detail tagihan tertentu.
     */
    public function show(Tagihan $tagihan)
    {
        // Petugas bisa melihat detail semua tagihan
        return view('petugas.tagihan.show', compact('tagihan'));
    }
}
