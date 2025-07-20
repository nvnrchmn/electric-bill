<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran; // Import Model Pembayaran
use App\Models\Tagihan; // Import Model Tagihan
use App\Models\Pelanggan; // Import Model Pelanggan (jika perlu)
use App\Models\User; // Import Model User (untuk yang mencatat pembayaran)
use Carbon\Carbon; // Untuk manipulasi tanggal

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource (daftar semua pembayaran).
     */
    public function index(Request $request)
    {
        $pembayaransQuery = Pembayaran::with(['tagihan.pelanggan', 'user']); // Load relasi

        // Filter berdasarkan bulan dan tahun
        $bulan = $request->input('bulan', date('m')); // Default bulan saat ini (misal '07')
        $tahun = $request->input('tahun', date('Y')); // Default tahun saat ini (misal '2025')

        if ($bulan !== 'all') {
            $pembayaransQuery->whereMonth('tanggal_pembayaran', (int) $bulan);
        }
        if ($tahun !== 'all') {
            $pembayaransQuery->whereYear('tanggal_pembayaran', (int) $tahun);
        }

        $pembayarans = $pembayaransQuery->orderBy('tanggal_pembayaran', 'desc')->get();

        // Data untuk dropdown filter bulan
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

        // Mengambil daftar tahun unik dari data pembayaran yang ada
        $years = Pembayaran::selectRaw('YEAR(tanggal_pembayaran) as year')->distinct()->orderBy('year', 'desc')->pluck('year');

        // Tambahkan tahun saat ini jika belum ada
        if (!$years->contains((int) date('Y'))) {
            $years->push((int) date('Y'));
            $years = $years->sortDesc();
        }
        // Tambahkan opsi 'Semua Tahun'
        $years->prepend('Semua Tahun', 'all');

        return view('admin.pembayarans.index', compact('pembayarans', 'bulan', 'tahun', 'months', 'years'));
    }

    /**
     * Menampilkan form untuk mencatat pembayaran baru untuk tagihan spesifik.
     */
    public function createPembayaran(Tagihan $tagihan)
    {
        // Pencegahan: Jika tagihan sudah dibayar, redirect kembali
        if ($tagihan->status === 'sudah_dibayar') {
            return redirect()->route('admin.tagihans.show', $tagihan->id_tagihan)->with('error', 'Tagihan ini sudah dibayar.');
        }

        // Load relasi pelanggan untuk form
        $tagihan->load('pelanggan');

        // Biaya admin default (bisa diatur dari konfigurasi atau input manual)
        $biayaAdminDefault = 2500;

        return view('admin.pembayarans.create', compact('tagihan', 'biayaAdminDefault'));
    }

    /**
     * Memproses dan menyimpan data pembayaran dari form.
     * Setelah disimpan, update status tagihan terkait.
     */
    public function storePembayaran(Request $request, Tagihan $tagihan)
    {
        // Validasi input dari form pembayaran
        $request->validate([
            'biaya_admin' => 'nullable|numeric|min:0', // Biaya admin bisa kosong atau 0
            'total_bayar' => 'required|numeric|min:' . $tagihan->total_tagihan, // Total bayar minimal harus sejumlah tagihan
        ]);

        // Pencegahan ganda: Pastikan tagihan belum dibayar saat proses penyimpanan
        if ($tagihan->status === 'sudah_dibayar') {
            return redirect()->route('admin.tagihans.show', $tagihan->id_tagihan)->with('error', 'Tagihan ini sudah dibayar.');
        }

        try {
            // Buat record Pembayaran baru
            Pembayaran::create([
                'id_tagihan' => $tagihan->id_tagihan,
                'id_pelanggan' => $tagihan->id_pelanggan,
                'tanggal_pembayaran' => Carbon::now()->toDateString(), // Tanggal pembayaran saat ini
                'bulan_bayar' => Carbon::now()->month, // Bulan pembayaran (numeric, e.g., 7 untuk Juli)
                'biaya_admin' => $request->biaya_admin ?? 0, // Ambil dari input atau 0 jika kosong
                'total_bayar' => $request->total_bayar, // Jumlah yang dibayarkan pelanggan
                'id_user' => auth()->id(), // ID user yang sedang login (admin/petugas)
            ]);

            // Update status tagihan terkait menjadi 'sudah_dibayar'
            $tagihan->update(['status' => 'sudah_dibayar']);

            return redirect()->route('admin.tagihans.index')->with('success', 'Pembayaran berhasil dicatat. Status tagihan diperbarui.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan atau update
            return redirect()
                ->back()
                ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource (menampilkan detail pembayaran).
     */
    public function show(Pembayaran $pembayaran)
    {
        // Load semua relasi yang diperlukan untuk detail
        $pembayaran->load(['tagihan.pelanggan.tarif', 'user.level']);
        return view('admin.pembayarans.show', compact('pembayaran'));
    }

    /**
     * Show the form for creating a new resource (tidak digunakan secara langsung).
     */
    public function create()
    {
        return redirect()->route('admin.tagihans.index')->with('info', 'Pembayaran dibuat melalui detail tagihan.');
    }

    /**
     * Store a newly created resource in storage (tidak digunakan secara langsung).
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.tagihans.index')->with('info', 'Gunakan fungsi createPembayaran dan storePembayaran.');
    }

    /**
     * Show the form for editing the specified resource (biasanya tidak untuk pembayaran).
     */
    public function edit(Pembayaran $pembayaran)
    {
        return redirect()->route('admin.pembayarans.index')->with('info', 'Pengeditan pembayaran biasanya tidak disarankan.');
    }

    /**
     * Update the specified resource in storage (biasanya tidak untuk pembayaran).
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        return redirect()->route('admin.pembayarans.index')->with('info', 'Pengeditan pembayaran biasanya tidak disarankan.');
    }

    /**
     * Remove the specified resource from storage (biasanya tidak disarankan).
     */
    public function destroy(Pembayaran $pembayaran)
    {
        // Pencegahan: Jangan izinkan penghapusan pembayaran yang sudah mengubah status tagihan
        if ($pembayaran->tagihan->status === 'sudah_dibayar') {
            return redirect()->back()->with('error', 'Pembayaran tidak dapat dihapus karena tagihan terkait sudah lunas.');
        }

        // Jika memang harus dihapus dan status tagihan belum lunas,
        // Anda mungkin perlu mengubah status tagihan kembali ke 'belum_dibayar'
        // $pembayaran->tagihan->update(['status' => 'belum_dibayar']);
        // $pembayaran->delete();

        return redirect()->back()->with('error', 'Penghapusan pembayaran tidak disarankan. Hubungi administrator.');
    }
}
