<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penggunaan; // Import model Penggunaan
use App\Models\Pelanggan; // Import model Pelanggan untuk dropdown
use Carbon\Carbon; // Untuk memanipulasi tanggal/bulan

class PenggunaanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua penggunaan.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', 'all');
        $pelangganFilter = $request->input('pelanggan', 'all');

        $years = Penggunaan::selectRaw('YEAR(tanggal_periksa) as year')->distinct()->orderBy('year', 'desc')->pluck('year');

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $pelanggans = Pelanggan::all();

        $query = Penggunaan::with('pelanggan');

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal_periksa', $bulan);
        }

        if ($tahun !== 'all') {
            $query->whereYear('tanggal_periksa', $tahun);
        }

        if ($pelangganFilter !== 'all') {
            $query->where('id_pelanggan', $pelangganFilter);
        }

        $penggunaans = $query->orderBy('tanggal_periksa', 'desc')->get();

        return view('admin.penggunaans.index', compact('penggunaans', 'bulan', 'tahun', 'years', 'months', 'pelanggans', 'pelangganFilter'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat penggunaan baru.
     */
    public function create()
    {
        $pelanggans = Pelanggan::all(); // Ambil semua pelanggan untuk dropdown
        return view('admin.penggunaans.create', compact('pelanggans'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan penggunaan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|min:0|gt:meter_awal',
        ]);

        // Gabungkan bulan dan tahun menjadi tanggal_periksa (misal: tanggal 1 setiap bulan)
        // INI adalah bagian yang mengisi 'tanggal_periksa' secara otomatis!
        $tanggalPeriksa = Carbon::create($request->tahun, $request->bulan, 1)->toDateString();

        // Cek apakah data penggunaan untuk pelanggan dan bulan/tahun yang sama sudah ada
        $existingPenggunaan = Penggunaan::where('id_pelanggan', $request->id_pelanggan)->where('bulan', $request->bulan)->where('tahun', $request->tahun)->first();

        if ($existingPenggunaan) {
            return redirect()
                ->back()
                ->withErrors(['bulan' => 'Data penggunaan untuk pelanggan ini pada bulan dan tahun tersebut sudah ada.'])
                ->withInput();
        }

        Penggunaan::create([
            'id_pelanggan' => $request->id_pelanggan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_periksa' => $tanggalPeriksa, // <-- Nilai di sini diambil dari $tanggalPeriksa yang dibuat otomatis
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
        ]);

        return redirect()->route('admin.penggunaans.index')->with('success', 'Data penggunaan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail penggunaan tertentu.
     */
    public function show(Penggunaan $penggunaan)
    {
        $penggunaan->load('pelanggan.tarif'); // Load relasi untuk detail
        return view('admin.penggunaans.show', compact('penggunaan'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit penggunaan tertentu.
     */
    public function edit(Penggunaan $penggunaan)
    {
        $pelanggans = Pelanggan::all();
        return view('admin.penggunaans.edit', compact('penggunaan', 'pelanggans'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui penggunaan tertentu di database.
     */
    public function update(Request $request, Penggunaan $penggunaan)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|min:0|gt:meter_awal',
        ]);

        // Gabungkan bulan dan tahun menjadi tanggal_periksa (misal: tanggal 1 setiap bulan)
        // INI juga yang mengisi 'tanggal_periksa' secara otomatis saat update!
        $tanggalPeriksa = Carbon::create($request->tahun, $request->bulan, 1)->toDateString();

        // Cek apakah data penggunaan untuk pelanggan dan bulan/tahun yang sama sudah ada (kecuali dirinya sendiri)
        $existingPenggunaan = Penggunaan::where('id_pelanggan', $request->id_pelanggan)->where('bulan', $request->bulan)->where('tahun', $request->tahun)->where('id_penggunaan', '!=', $penggunaan->id_penggunaan)->first();

        if ($existingPenggunaan) {
            return redirect()
                ->back()
                ->withErrors(['bulan' => 'Data penggunaan untuk pelanggan ini pada bulan dan tahun tersebut sudah ada.'])
                ->withInput();
        }

        $penggunaan->update([
            'id_pelanggan' => $request->id_pelanggan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_periksa' => $tanggalPeriksa, // <-- Nilai di sini diambil dari $tanggalPeriksa
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
        ]);

        return redirect()->route('admin.penggunaans.index')->with('success', 'Data penggunaan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus penggunaan tertentu dari database.
     */
    public function destroy(Penggunaan $penggunaan)
    {
        // Pencegahan: Jika penggunaan ini sudah memiliki tagihan terkait, jangan izinkan dihapus
        if ($penggunaan->tagihan()->where('status', '!=', 'dibatalkan')->exists()) {
            // Pastikan relasi tagihan sudah didefinisikan di model Penggunaan
            return redirect()->route('admin.penggunaans.index')->with('error', 'Data penggunaan tidak bisa dihapus karena sudah memiliki tagihan terkait.');
        }

        $penggunaan->delete();

        return redirect()->route('admin.penggunaans.index')->with('success', 'Data penggunaan berhasil dihapus!');
    }
}
