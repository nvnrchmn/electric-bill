<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Import model User
use App\Models\Level; // Import model Level untuk dropdown
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rule; // Untuk validasi unique kecuali diri sendiri
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua user (admin & petugas).
     */
    public function index(Request $request)
    {
        $query = User::with('level');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('nama_admin', 'like', '%' . $search . '%')
                    ->orWhereHas('level', function ($q2) use ($search) {
                        $q2->where('nama_level', 'like', '%' . $search . '%');
                    });
            });
        }

        $users = Cache::remember('users_page_' . $request->page . '_search_' . $request->search, 60, function () use ($query) {
            return $query->paginate(10);
        });

        // Paginasi 10 item per halaman
        // $users = $query->paginate(10);

        // Menambahkan query string pencarian ke link paginasi
        $users->appends(['search' => $request->search]);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        $levels = Level::all(); // Ambil semua level untuk dropdown
        return view('admin.users.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari password_confirmation
            'nama_admin' => 'nullable|string|max:255', // Nama admin bisa kosong
            'id_level' => 'required|exists:levels,id_level', // Harus ada di tabel levels
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password sebelum disimpan!
            'nama_admin' => $request->nama_admin,
            'id_level' => $request->id_level,
            'email_verified_at' => now(), // Verifikasi email instan untuk user yang dibuat admin
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail user tertentu.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit user tertentu.
     */
    public function edit(User $user)
    {
        $levels = Level::all();
        return view('admin.users.edit', compact('user', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui user tertentu di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id_user, 'id_user'), // Abaikan username saat ini
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id_user, 'id_user'), // Abaikan email saat ini
            ],
            'password' => 'nullable|string|min:8|confirmed', // Password bisa kosong jika tidak diubah
            'nama_admin' => 'nullable|string|max:255',
            'id_level' => 'required|exists:levels,id_level',
        ]);

        $userData = [
            'username' => $request->username,
            'email' => $request->email,
            'nama_admin' => $request->nama_admin,
            'id_level' => $request->id_level,
        ];

        if ($request->filled('password')) {
            // Hanya update password jika diisi
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus user tertentu dari database.
     */
    public function destroy(User $user)
    {
        // Pencegahan: Admin tidak bisa menghapus akunnya sendiri
        if (auth()->user()->id_user === $user->id_user) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Anda mungkin ingin menambahkan logika pencegahan lain, misalnya
        // tidak boleh menghapus jika user masih terhubung dengan pembayaran atau data penting lainnya.
        // if ($user->pembayarans()->count() > 0) { ... }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
