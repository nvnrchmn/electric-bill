@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Manajemen User (Admin & Petugas)</h1>

            {{-- Alert untuk pesan sukses/error --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="alert alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User Baru</a>

                {{-- Kolom Pencarian Langsung --}}
                <div class="form-control">
                    <input type="text" placeholder="Cari user..." class="input input-bordered w-full md:w-auto"
                        id="searchInput" value="{{ request('search') }}">
                </div>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID User</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Admin/Petugas</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id_user }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->nama_admin ?? '-' }}</td>
                                <td>{{ $user->level->nama_level ?? 'Tidak Diketahui' }}</td>
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id_user) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data user yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            <div class="mt-4">
                {{ $users->links() }} {{-- Ini akan merender link paginasi --}}
            </div>
        </div>
    </div>

    {{-- Script JavaScript untuk Pencarian Langsung (Tanpa Tombol) --}}
    @push('scripts')
        {{-- Jika Anda menggunakan @push('scripts') di layout Anda --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                let typingTimer; // Timer untuk menunda pengiriman permintaan
                const doneTypingInterval = 500; // Waktu tunda 500ms setelah mengetik berhenti

                searchInput.addEventListener('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => {
                        const searchValue = searchInput.value;
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('search', searchValue);
                        currentUrl.searchParams.delete(
                        'page'); // Reset halaman ke 1 saat pencarian baru
                        window.location.href = currentUrl.toString();
                    }, doneTypingInterval);
                });
            });
        </script>
    @endpush
@endsection
