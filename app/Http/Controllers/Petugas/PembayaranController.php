<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['tagihan.pelanggan', 'pelanggan', 'user']) // Load relasi pelanggan secara langsung juga
            ->orderBy('tanggal_pembayaran', 'desc')
            ->orderBy('created_at', 'desc');

        $bulanFilter = $request->input('bulan', 'all');
        $tahunFilter = $request->input('tahun', 'all');

        if ($bulanFilter !== 'all') {
            // Filter berdasarkan bulan_bayar di tabel pembayaran, jika itu yang dimaksud
            $query->where('bulan_bayar', $bulanFilter);
            // Atau jika Anda mau filter berdasarkan bulan tagihan, gunakan:
            // $query->whereHas('tagihan', function($q) use ($bulanFilter) {
            //     $q->where('bulan', str_pad($bulanFilter, 2, '0', STR_PAD_LEFT));
            // });
        }
        if ($tahunFilter !== 'all') {
            // Filter berdasarkan tanggal_pembayaran di tabel pembayaran
            $query->whereYear('tanggal_pembayaran', $tahunFilter);
            // Atau jika Anda mau filter berdasarkan tahun tagihan, gunakan:
            // $query->whereHas('tagihan', function($q) use ($tahunFilter) {
            //     $q->where('tahun', $tahunFilter);
            // });
        }

        $pembayarans = $query->paginate(10);

        $months = [
            'all' => 'Semua Bulan',
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Desember',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // Ambil tahun dari kolom tanggal_pembayaran pada tabel pembayarans
        $years = Pembayaran::selectRaw('YEAR(tanggal_pembayaran) as year_val')->distinct()->orderBy('year_val', 'desc')->pluck('year_val');
        $currentYear = (int) date('Y');
        if (!$years->contains($currentYear)) {
            $years->push($currentYear);
            $years = $years->sortDesc();
        }
        $years = $years->mapWithKeys(fn($year) => [(string) $year => (string) $year])->prepend('Semua Tahun', 'all'); // Convert to string for consistency

        return view('petugas.pembayaran.index', compact('pembayarans', 'months', 'years', 'bulanFilter', 'tahunFilter'));
    }

    public function create(Request $request)
    {
        $tagihanId = $request->query('tagihan_id');
        $selectedTagihan = null;

        // Cari tagihan yang berstatus 'belum_dibayar'
        $queryTagihan = Tagihan::with('pelanggan')
            ->where('status', 'belum_dibayar') // Tetap 'belum_dibayar' sesuai migration Anda
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc');

        if ($tagihanId) {
            $selectedTagihan = $queryTagihan->find($tagihanId);
            if (!$selectedTagihan) {
                return redirect()->route('petugas.pembayaran.create')->with('error', 'Tagihan tidak ditemukan atau sudah dibayar.');
            }
        }

        $tagihans = $queryTagihan->get();

        return view('petugas.pembayaran.create', compact('tagihans', 'selectedTagihan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tagihan' => 'required|exists:tagihans,id_tagihan',
            'biaya_admin' => 'required|numeric|min:0', // Validasi biaya admin
            'jumlah_uang_dibayar' => 'required|numeric|min:0', // Ini adalah input jumlah uang yang diterima
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tagihan = Tagihan::find($request->id_tagihan);

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan tidak ditemukan.');
        }

        if ($tagihan->status !== 'belum_dibayar') {
            return redirect()->back()->with('error', 'Tagihan ini sudah dibayar atau memiliki status lain.');
        }

        $biayaAdmin = $request->biaya_admin;
        $jumlahUangDibayar = $request->jumlah_uang_dibayar;
        $totalTagihanAsli = $tagihan->total_tagihan; // Mengambil total_tagihan dari model Tagihan

        // Hitung total_bayar yang harus dicatat di pembayaran (total tagihan + biaya admin)
        $totalYangHarusDibayar = $totalTagihanAsli + $biayaAdmin;

        // Validasi apakah uang yang dibayar mencukupi
        if ($jumlahUangDibayar < $totalYangHarusDibayar) {
            return redirect()->back()->with('error', 'Jumlah uang pembayaran kurang dari total tagihan ditambah biaya admin.');
        }

        Pembayaran::create([
            'id_tagihan' => $request->id_tagihan,
            'id_pelanggan' => $tagihan->id_pelanggan, // Ambil id_pelanggan dari tagihan
            'id_user' => Auth::id(),
            'tanggal_pembayaran' => Carbon::now(),
            'bulan_bayar' => Carbon::now()->format('m'), // Asumsi bulan_bayar adalah bulan saat ini
            'biaya_admin' => $biayaAdmin,
            'total_bayar' => $jumlahUangDibayar, // total_bayar di pembayaran adalah uang yang diterima
            'keterangan' => $request->keterangan,
        ]);

        // Update status tagihan menjadi 'sudah_dibayar'
        $tagihan->update(['status' => 'sudah_dibayar']);

        return redirect()->route('petugas.pembayaran.index')->with('success', 'Pembayaran berhasil dicatat dan status tagihan diperbarui.');
    }

    public function show(Pembayaran $pembayaran)
    {
        // Pastikan relasi pelanggan diload langsung dari pembayaran
        $pembayaran->load(['tagihan.penggunaan', 'tagihan.pelanggan', 'pelanggan', 'user']);
        return view('petugas.pembayaran.show', compact('pembayaran'));
    }
}
