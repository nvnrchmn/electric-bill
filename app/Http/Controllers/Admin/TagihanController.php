<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan; // Import Model Tagihan
use App\Models\Penggunaan; // Import Model Penggunaan (jika nanti dibutuhkan)
use App\Models\Pelanggan; // Import Model Pelanggan (jika nanti dibutuhkan)
use Carbon\Carbon; // Jika nanti perlu manipulasi tanggal

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter opsional untuk tagihan (mirip dengan filter penggunaan)
        // $bulan defaultnya 'm' akan menghasilkan bulan saat ini sebagai string, misal '07'
        $bulan = $request->input('bulan', date('m'));
        // $tahun defaultnya 'Y' akan menghasilkan tahun saat ini sebagai string, misal '2025'
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status', 'all'); // Tambahan filter status, default 'all'

        // Memuat relasi yang diperlukan untuk ditampilkan di tabel
        $tagihansQuery = Tagihan::with(['penggunaan.pelanggan.tarif']);

        // Menerapkan filter berdasarkan input dari request
        if ($bulan !== 'all') {
            // Penting: Konversi nilai bulan dari request ke integer
            // karena kolom 'bulan' di database adalah integer.
            $tagihansQuery->where('bulan', (int) $bulan);
        }
        if ($tahun !== 'all') {
            // Penting: Konversi nilai tahun dari request ke integer
            // karena kolom 'tahun' di database adalah integer.
            $tagihansQuery->where('tahun', (int) $tahun);
        }
        if ($status !== 'all') {
            $tagihansQuery->where('status', $status);
        }

        // Mengambil data tagihan setelah filter diterapkan, diurutkan
        $tagihans = $tagihansQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        // Data untuk dropdown filter bulan (lengkap)
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

        // Mengambil daftar tahun unik dari data tagihan di database
        // Menggunakan select('tahun') karena kolom 'tahun' sudah integer, bukan DATETIME.
        $years = Tagihan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Tambahkan tahun saat ini jika belum ada di daftar tahun yang ada di database
        if (!$years->contains((int) date('Y'))) {
            $years->push((int) date('Y'));
            $years = $years->sortDesc(); // Pastikan urutan tetap descending
        }
        // Tambahkan opsi 'Semua Tahun' di awal collection
        $years->prepend('Semua Tahun', 'all'); // 'all' sebagai value

        // Daftar status untuk dropdown filter
        $statuses = [
            'all' => 'Semua Status',
            'belum_dibayar' => 'Belum Dibayar',
            'sudah_dibayar' => 'Sudah Dibayar',
            'dibatalkan' => 'Dibatalkan',
        ];

        // Mengirimkan data ke view
        return view('admin.tagihans.index', compact('tagihans', 'bulan', 'tahun', 'status', 'months', 'years', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tagihan tidak dibuat dari form terpisah, tapi dari Penggunaan.
        // Metode ini mungkin tidak terpakai atau bisa dialihkan.
        return redirect()->route('admin.penggunaans.index')->with('info', 'Tagihan dibuat melalui data penggunaan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tagihan tidak dibuat dari form terpisah.
        // Logic untuk generate tagihan akan ada di metode `generateTagihan`.
        return redirect()->route('admin.penggunaans.index')->with('error', 'Metode ini tidak digunakan untuk membuat tagihan.');
    }

    /**
     * Custom method to generate a Tagihan from a Penggunaan.
     */
    public function generateTagihan(Penggunaan $penggunaan)
    {
        // 1. Cek apakah tagihan untuk penggunaan ini sudah ada
        if ($penggunaan->tagihans()->exists()) {
            return redirect()->back()->with('error', 'Tagihan untuk penggunaan ini sudah ada.');
        }

        // 2. Ambil data pelanggan dan tarif terkait
        $pelanggan = $penggunaan->pelanggan;
        if (!$pelanggan || !$pelanggan->tarif) {
            return redirect()->back()->with('error', 'Data pelanggan atau tarif tidak ditemukan untuk penggunaan ini.');
        }

        $tarifPerKWH = $pelanggan->tarif->tarifperkwh; // Ambil tarif per KWH
        $jumlahMeter = $penggunaan->meter_akhir - $penggunaan->meter_awal;

        // 3. Hitung total tagihan
        $totalTagihan = $jumlahMeter * $tarifPerKWH;

        // 4. Buat record Tagihan baru
        try {
            Tagihan::create([
                'id_penggunaan' => $penggunaan->id_penggunaan,
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'bulan' => $penggunaan->bulan,
                'tahun' => $penggunaan->tahun,
                'jumlah_meter' => $jumlahMeter,
                'total_tagihan' => $totalTagihan,
                'status' => 'belum_dibayar', // Default status
            ]);
            return redirect()->back()->with('success', 'Tagihan berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal membuat tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tagihan $tagihan)
    {
        $tagihan->load('penggunaan.pelanggan.tarif'); // Load relasi yang diperlukan
        return view('admin.tagihans.show', compact('tagihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        // Umumnya tagihan yang sudah dibuat tidak diedit kecuali statusnya.
        // Mungkin kita hanya izinkan edit status, bukan nilai lainnya.
        return redirect()->route('admin.tagihans.index')->with('info', 'Edit tagihan umumnya terbatas pada status.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        // Validasi untuk update status tagihan, dll.
        // Misal: $request->validate(['status' => 'required|in:belum_dibayar,sudah_dibayar,dibatalkan']);
        // $tagihan->update($request->all());
        return redirect()->route('admin.tagihans.index')->with('success', 'Tagihan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        // Pencegahan: Jangan hapus tagihan jika sudah dibayar
        if ($tagihan->status === 'sudah_dibayar') {
            return redirect()->back()->with('error', 'Tagihan tidak dapat dihapus karena sudah dibayar.');
        }

        $tagihan->delete();
        return redirect()->back()->with('success', 'Tagihan berhasil dihapus!');
    }
}
