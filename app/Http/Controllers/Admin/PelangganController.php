<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan; // Import model Pelanggan
use App\Models\Tarif; // Import model Tarif untuk dropdown
use App\Models\User; // Import model User jika pelanggan bisa dihubungkan ke user login
use App\Models\Level; // Import model Level
use Illuminate\Validation\Rule; // Untuk validasi unique kecuali diri sendiri

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua pelanggan.
     */
    public function index(Request $request)
    {
        $query = Pelanggan::with(['tarif', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query
                ->where('username', 'like', "%$search%")
                ->orWhere('nomor_kwh', 'like', "%$search%")
                ->orWhere('nama_pelanggan', 'like', "%$search%")
                ->orWhere('alamat', 'like', "%$search%");
        }

        $pelanggans = $query->paginate(10);
        $pelanggans->appends(['search' => $request->search]);

        return view('admin.pelanggans.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat pelanggan baru.
     */
    public function create()
    {
        $tarifs = Tarif::all();
        // Ambil user yang bisa jadi pemilik/pengontrol pelanggan.
        // Misalnya, semua user kecuali Pelanggan (jika pelanggan login via tabel user)
        // Atau hanya Admin dan Petugas.
        // Atau bahkan semua user, termasuk pelanggan, jika pelanggan bisa jadi pemilik pelanggan lain.
        $users = User::whereIn('id_level', [Level::ADMINISTRATOR_ID, Level::PETUGAS_ID, Level::PELANGGAN_ID])->get();

        return view('admin.pelanggans.create', compact('tarifs', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:pelanggans,username',
            'nomor_kwh' => 'required|string|max:255|unique:pelanggans,nomor_kwh',
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'id_tarif' => 'required|exists:tarifs,id_tarif',
            'id_user' => 'nullable|exists:users,id_user', // id_user bisa null (jika belum ditentukan pemiliknya)
        ]);

        Pelanggan::create($request->all()); // Sekarang $request->all() akan menyertakan id_user

        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail pelanggan tertentu.
     */
    public function show(Pelanggan $pelanggan)
    {
        // Load relasi tarif untuk ditampilkan di view
        $pelanggan->load('tarif');
        return view('admin.pelanggans.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit pelanggan tertentu.
     */
    public function edit(Pelanggan $pelanggan)
    {
        $tarifs = Tarif::all();
        $users = User::whereIn('id_level', [Level::ADMINISTRATOR_ID, Level::PETUGAS_ID, Level::PELANGGAN_ID])->get();
        return view('admin.pelanggans.edit', compact('pelanggan', 'tarifs', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui pelanggan tertentu di database.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('pelanggans', 'username')->ignore($pelanggan->id_pelanggan, 'id_pelanggan')],
            'nomor_kwh' => ['required', 'string', 'max:255', Rule::unique('pelanggans', 'nomor_kwh')->ignore($pelanggan->id_pelanggan, 'id_pelanggan')],
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'id_tarif' => 'required|exists:tarifs,id_tarif',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus pelanggan tertentu dari database.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        // Pencegahan: Jangan biarkan menghapus pelanggan jika ada data penggunaan atau pembayaran terkait
        if ($pelanggan->penggunaans()->count() > 0 || $pelanggan->pembayarans()->count() > 0) {
            return redirect()->route('admin.pelanggans.index')->with('error', 'Pelanggan tidak bisa dihapus karena memiliki data penggunaan atau pembayaran terkait.');
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil dihapus!');
    }
}
