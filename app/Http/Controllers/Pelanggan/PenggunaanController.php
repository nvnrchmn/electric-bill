<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penggunaan;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class PenggunaanController extends Controller
{
    /**
     * Display a listing of the customer's usage history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pelanggans = $user->pelanggans;

        if ($pelanggans->isEmpty()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan untuk melihat riwayat penggunaan.');
        }

        $bulanFilter = $request->input('bulan', 'all');
        $tahunFilter = $request->input('tahun', 'all');

        $penggunaans = $pelanggans->flatMap(function ($pelanggan) {
            return $pelanggan->penggunaans()->with('tarif', 'user')->get();
        });

        if ($bulanFilter !== 'all') {
            $penggunaans = $penggunaans->where('bulan', str_pad($bulanFilter, 2, '0', STR_PAD_LEFT));
        }
        if ($tahunFilter !== 'all') {
            $penggunaans = $penggunaans->where('tahun', $tahunFilter);
        }

        // $penggunaans = $penggunaans->sortByDesc('tahun')->sortByDesc('bulan')->paginate(10);
        $penggunaans = $penggunaans->sortByDesc('tahun')->sortByDesc('bulan');

        $penggunaans = $this->paginateCollection($penggunaans, 10);

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

        $years = $penggunaans->pluck('tahun')->unique()->sortDesc()->values()->all();
        $currentYear = (string) Carbon::now()->year;
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
            rsort($years);
        }
        array_unshift($years, 'all');
        $years = array_combine($years, array_map(fn($y) => $y === 'all' ? 'Semua Tahun' : $y, $years));

        return view('pelanggan.penggunaan.index', compact('penggunaans', 'months', 'years', 'bulanFilter', 'tahunFilter'));
    }

    /**
     * Display the specified penggunaan.
     */
    public function show(Penggunaan $penggunaan)
    {
        $user = Auth::user();
        $pelangganIds = $user->pelanggans->pluck('id_pelanggan')->toArray();

        if (!in_array($penggunaan->id_pelanggan, $pelangganIds)) {
            abort(403, 'Anda tidak memiliki akses ke data penggunaan ini.');
        }

        $penggunaan->load('tarif', 'user');

        return view('pelanggan.penggunaan.show', compact('penggunaan'));
    }

    protected function paginateCollection($items, $perPage = 10, $page = null)
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage();
        $items = $items instanceof \Illuminate\Support\Collection ? $items : collect($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
    }
}
