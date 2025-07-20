<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level; // Import model Level
use RealRashid\SweetAlert\Facades\Alert; // Nanti akan kita instal jika diperlukan

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua level.
     */
    public function index()
    {
        $levels = Level::all(); // Mengambil semua data level
        return view('admin.levels.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat level baru.
     */
    public function create()
    {
        return view('admin.levels.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan level baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_level' => 'required|string|max:255|unique:levels,nama_level', // Nama level harus unik
        ]);

        Level::create($request->all());

        // Alert::success('Berhasil', 'Level berhasil ditambahkan.'); // SweetAlert, uncomment jika sudah diinstal
        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail level tertentu (jarang digunakan untuk level, tapi tetap ada).
     */
    public function show(Level $level)
    {
        return view('admin.levels.show', compact('level'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit level tertentu.
     */
    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui level tertentu di database.
     */
    public function update(Request $request, Level $level)
    {
        $request->validate([
            'nama_level' => 'required|string|max:255|unique:levels,nama_level,' . $level->id_level . ',id_level', // Nama level harus unik kecuali untuk dirinya sendiri
        ]);

        $level->update($request->all());

        // Alert::success('Berhasil', 'Level berhasil diperbarui.'); // SweetAlert, uncomment jika sudah diinstal
        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus level tertentu dari database.
     */
    public function destroy(Level $level)
    {
        // Pencegahan: Jangan biarkan menghapus level yang sudah terhubung dengan user
        // Anda mungkin ingin menambahkan logika untuk memeriksa apakah ada user yang masih menggunakan level ini
        if ($level->users()->count() > 0) {
            // Alert::error('Gagal', 'Level tidak bisa dihapus karena masih ada user yang menggunakannya.'); // SweetAlert
            return redirect()->route('admin.levels.index')->with('error', 'Level tidak bisa dihapus karena masih ada user yang menggunakannya.');
        }

        $level->delete();

        // Alert::success('Berhasil', 'Level berhasil dihapus.'); // SweetAlert
        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil dihapus!');
    }
}
