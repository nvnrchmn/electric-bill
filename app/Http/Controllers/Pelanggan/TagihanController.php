<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan data pelanggan yang login
use App\Models\Tagihan;
use Carbon\Carbon; // Untuk filter bulan/tahun

class TagihanController extends Controller
{
    /**
     * Display a listing of the customer's bills.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pelangganIds = $user->pelanggans->pluck('id_pelanggan');

        if ($pelangganIds->isEmpty()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan untuk melihat tagihan.');
        }

        // Filter bulan dan tahun
        $bulanFilter = $request->input('bulan', 'all');
        $tahunFilter = $request->input('tahun', 'all');

        $query = Tagihan::whereIn('id_pelanggan', $pelangganIds)->with('penggunaan.tarif')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');

        if ($bulanFilter !== 'all') {
            $query->where('bulan', str_pad($bulanFilter, 2, '0', STR_PAD_LEFT));
        }

        if ($tahunFilter !== 'all') {
            $query->where('tahun', $tahunFilter);
        }

        $tagihans = $query->paginate(10);

        // Data untuk dropdown bulan
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

        // Filter tahun yang tersedia di tagihan
        $years = Tagihan::whereIn('id_pelanggan', $pelangganIds)->selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun')->toArray();

        $currentYear = (string) Carbon::now()->year;
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
            rsort($years);
        }

        array_unshift($years, 'all');
        $years = array_combine($years, array_map(fn($y) => $y === 'all' ? 'Semua Tahun' : $y, $years));

        return view('pelanggan.tagihan.index', [
            'tagihans' => $tagihans,
            'months' => $months,
            'years' => $years,
            'bulanFilter' => $bulanFilter,
            'tahunFilter' => $tahunFilter,
            'pelangganIds' => $pelangganIds,
        ]);
    }

    /**
     * Display the specified tagihan.
     */
    public function show(Tagihan $tagihan)
    {
        $user = Auth::user();

        // Cek apakah tagihan ini memang milik salah satu pelanggan milik user
        $pelangganIds = $user->pelanggans->pluck('id_pelanggan')->toArray();

        if (!in_array($tagihan->id_pelanggan, $pelangganIds)) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        $tagihan->load('penggunaan.tarif', 'pembayarans');
        // dd($tagihan->penggunaan->pelanggan->tarif->tarifperkwh);
        return view('pelanggan.tagihan.show', compact('tagihan'));
    }

    public function bayar(Request $request, $id)
    {
        $user = Auth::user();
        $tagihan = Tagihan::where('id_tagihan', $id)
            ->whereIn('id_pelanggan', $user->pelanggans->pluck('id_pelanggan'))
            ->firstOrFail();

        if ($tagihan->status === 'sudah_dibayar') {
            return back()->with('error', 'Tagihan ini sudah dibayar.');
        }

        $metode = $request->input('metode_pembayaran', 'transfer');

        // Simpan pembayaran
        $tagihan->pembayarans()->create([
            'id_pelanggan' => $tagihan->id_pelanggan,
            'id_tagihan' => $tagihan->id_tagihan,
            'id_user' => $user->id_user,
            'bulan_bayar' => $tagihan->bulan,
            'tahun' => $tagihan->tahun,
            'tanggal_periksa' => $tagihan->tanggal_periksa,
            'tanggal_pembayaran' => now(),
            'total_bayar' => $tagihan->total_tagihan,
            'metode_pembayaran' => $metode,
        ]);

        // Update status tagihan
        $tagihan->update(['status' => 'sudah_dibayar']);

        return back()->with('success', "Pembayaran berhasil menggunakan metode: $metode.");
    }
}
