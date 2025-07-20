<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Tampilkan riwayat pembayaran semua pelanggan milik user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pelangganIds = $user->pelanggans->pluck('id_pelanggan');

        if ($pelangganIds->isEmpty()) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki pelanggan untuk melihat riwayat pembayaran.');
        }

        // Filter berdasarkan pelanggan & tahun
        $filterPelanggan = $request->input('pelanggan_id', 'all');
        $filterTahun = $request->input('tahun', 'all');

        $query = Pembayaran::with(['pelanggan', 'tagihan'])->whereIn('id_pelanggan', $pelangganIds);

        if ($filterPelanggan !== 'all') {
            $query->where('id_pelanggan', $filterPelanggan);
        }

        if ($filterTahun !== 'all') {
            $query->whereYear('tanggal_pembayaran', $filterTahun);
        }

        $pembayarans = $query->orderByDesc('tanggal_pembayaran')->paginate(10);

        // Data untuk dropdown filter pelanggan
        $pelangganOptions = $user->pelanggans->pluck('nama_pelanggan', 'id_pelanggan')->prepend('Semua Pelanggan', 'all');

        // Data untuk dropdown filter tahun
        $tahunOptions = Pembayaran::whereIn('id_pelanggan', $pelangganIds)->selectRaw('DISTINCT YEAR(tanggal_pembayaran) as tahun')->orderByDesc('tahun')->pluck('tahun')->toArray();

        $currentYear = Carbon::now()->year;
        if (!in_array($currentYear, $tahunOptions)) {
            $tahunOptions[] = $currentYear;
            rsort($tahunOptions);
        }
        array_unshift($tahunOptions, 'all');
        $tahunOptions = array_combine($tahunOptions, array_map(fn($y) => $y === 'all' ? 'Semua Tahun' : $y, $tahunOptions));

        return view('pelanggan.pembayaran.index', compact('pembayarans', 'pelangganOptions', 'filterPelanggan', 'tahunOptions', 'filterTahun'));
    }
}
