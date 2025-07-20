<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarif; // Import model Tarif
use Illuminate\Validation\Rule; // Untuk validasi unique kecuali diri sendiri

class TarifController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua tarif.
     */
    public function index()
    {
        $tarifs = Tarif::all(); // Mengambil semua data tarif
        return view('admin.tarifs.index', compact('tarifs'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat tarif baru.
     */
    public function create()
    {
        return view('admin.tarifs.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan tarif baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'daya' => 'required|string|max:255|unique:tarifs,daya', // Daya harus unik
            'tarifperkwh' => 'required|numeric|min:0', // Tarif per KWH harus numerik dan tidak negatif
        ]);

        Tarif::create($request->all());

        return redirect()->route('admin.tarifs.index')->with('success', 'Tarif berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail tarif tertentu (jarang digunakan).
     */
    public function show(Tarif $tarif)
    {
        return view('admin.tarifs.show', compact('tarif'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit tarif tertentu.
     */
    public function edit(Tarif $tarif)
    {
        return view('admin.tarifs.edit', compact('tarif'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui tarif tertentu di database.
     */
    public function update(Request $request, Tarif $tarif)
    {
        $request->validate([
            'daya' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tarifs', 'daya')->ignore($tarif->id_tarif, 'id_tarif'), // Abaikan daya saat ini
            ],
            'tarifperkwh' => 'required|numeric|min:0',
        ]);

        $tarif->update($request->all());

        return redirect()->route('admin.tarifs.index')->with('success', 'Tarif berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus tarif tertentu dari database.
     */
    public function destroy(Tarif $tarif)
    {
        // Pencegahan: Jangan biarkan menghapus tarif jika ada pelanggan yang masih menggunakannya
        if ($tarif->pelanggans()->count() > 0) {
            return redirect()->route('admin.tarifs.index')->with('error', 'Tarif tidak bisa dihapus karena masih ada pelanggan yang menggunakannya.');
        }

        $tarif->delete();

        return redirect()->route('admin.tarifs.index')->with('success', 'Tarif berhasil dihapus!');
    }
}
