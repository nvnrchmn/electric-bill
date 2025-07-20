<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penggunaan; // Model Penggunaan
use App\Models\Pelanggan; // Model Pelanggan (untuk dropdown saat menambah penggunaan)
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID petugas yang login
use Carbon\Carbon; // Untuk mempermudah penanganan tanggal dan bulan

class PenggunaanController extends Controller
{
    /**
     * Menampilkan daftar penggunaan listrik yang dicatat oleh petugas ini.
     * Atau, jika ingin melihat semua penggunaan, bisa ditambahkan filter/logika.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil penggunaan yang dicatat oleh petugas yang sedang login
        // Anda bisa menambahkan filter bulan/tahun di sini jika diperlukan
        $query = Penggunaan::with('pelanggan') // Load relasi pelanggan
            ->where('petugas_id', $user->id_user) // Hanya penggunaan yang dicatat oleh petugas ini
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('created_at', 'desc');

        // Contoh filter sederhana (opsional, bisa dikembangkan)
        $bulanFilter = $request->input('bulan', 'all');
        $tahunFilter = $request->input('tahun', 'all');

        if ($bulanFilter !== 'all') {
            $query->where('bulan', (int) $bulanFilter);
        }
        if ($tahunFilter !== 'all') {
            $query->where('tahun', (int) $tahunFilter);
        }

        $penggunaans = $query->paginate(10); // Paginate untuk performa

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

        // Ambil daftar tahun unik dari penggunaan atau buat rentang tahun
        $years = Penggunaan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $currentYear = (int) date('Y');
        if (!$years->contains($currentYear)) {
            $years->push($currentYear);
            $years = $years->sortDesc();
        }
        $years = $years->mapWithKeys(fn($year) => [$year => $year])->prepend('Semua Tahun', 'all'); // Format for dropdown

        return view('petugas.penggunaan.index', compact('penggunaans', 'months', 'years', 'bulanFilter', 'tahunFilter'));
    }

    /**
     * Menampilkan form untuk menambah data penggunaan baru.
     */
    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get(); // Ambil semua pelanggan untuk dropdown
        return view('petugas.penggunaan.create', compact('pelanggans'));
    }

    /**
     * Menyimpan data penggunaan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan', // <<< UBAH BARIS INI
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:' . (date('Y') + 1),
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|gt:meter_awal',
        ]);

        // Cek apakah sudah ada penggunaan untuk pelanggan, bulan, dan tahun yang sama
        $existingPenggunaan = Penggunaan::where('id_pelanggan', $request->id_pelanggan)->where('bulan', $request->bulan)->where('tahun', $request->tahun)->first();

        if ($existingPenggunaan) {
            return redirect()->back()->with('error', 'Data penggunaan untuk pelanggan ini pada bulan dan tahun yang sama sudah ada.')->withInput();
        }

        // Simpan data penggunaan
        Penggunaan::create([
            'id_pelanggan' => $request->id_pelanggan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_periksa' => Carbon::now(),
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->route('petugas.penggunaan.index')->with('success', 'Data penggunaan listrik berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail penggunaan tertentu.
     */
    public function show(Penggunaan $penggunaan)
    {
        // Pastikan petugas hanya bisa melihat penggunaan miliknya sendiri
        if ($penggunaan->petugas_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan melihat data penggunaan ini.');
        }
        return view('petugas.penggunaan.show', compact('penggunaan'));
    }

    /**
     * Menampilkan form untuk mengedit data penggunaan.
     */
    public function edit(Penggunaan $penggunaan)
    {
        // Pastikan petugas hanya bisa mengedit penggunaan miliknya sendiri
        // Atau tambahkan logika lain, misal hanya bisa edit dalam periode tertentu
        if ($penggunaan->petugas_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit data penggunaan ini.');
        }

        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        return view('petugas.penggunaan.edit', compact('penggunaan', 'pelanggans'));
    }

    /**
     * Memperbarui data penggunaan di database.
     */
    public function update(Request $request, Penggunaan $penggunaan)
    {
        // Pastikan petugas hanya bisa mengedit penggunaan miliknya sendiri
        if ($penggunaan->petugas_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan memperbarui data penggunaan ini.');
        }

        // Validasi input
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan', // <<< UBAH BARIS INI
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:' . (date('Y') + 1),
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|gt:meter_awal',
        ]);

        // Cek duplikasi kecuali untuk data yang sedang diedit
        $existingPenggunaan = Penggunaan::where('id_pelanggan', $request->id_pelanggan)->where('bulan', $request->bulan)->where('tahun', $request->tahun)->where('id_penggunaan', '!=', $penggunaan->id_penggunaan)->first();

        if ($existingPenggunaan) {
            return redirect()->back()->with('error', 'Data penggunaan untuk pelanggan ini pada bulan dan tahun yang sama sudah ada.')->withInput();
        }

        $penggunaan->update([
            'id_pelanggan' => $request->id_pelanggan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
        ]);

        return redirect()->route('petugas.penggunaan.index')->with('success', 'Data penggunaan listrik berhasil diperbarui.');
    }

    /**
     * Menghapus data penggunaan dari database.
     */
    public function destroy(Penggunaan $penggunaan)
    {
        // Pastikan petugas hanya bisa menghapus penggunaan miliknya sendiri
        if ($penggunaan->petugas_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan menghapus data penggunaan ini.');
        }

        // Tambahkan kondisi: tidak bisa dihapus jika sudah ada tagihan terkait
        if ($penggunaan->tagihan()->exists()) {
            return redirect()->back()->with('error', 'Data penggunaan tidak bisa dihapus karena sudah memiliki tagihan terkait.');
        }

        $penggunaan->delete();
        return redirect()->route('petugas.penggunaan.index')->with('success', 'Data penggunaan listrik berhasil dihapus.');
    }
}
