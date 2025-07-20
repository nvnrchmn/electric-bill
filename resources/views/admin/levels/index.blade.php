@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Manajemen Level Pengguna</h1>

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

            <div class="mb-4">
                <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">Tambah Level Baru</a>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="table w-full text-base-content">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Level</th>
                            <th>Dibuat Pada</th>
                            <th>Diperbarui Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($levels as $level)
                            <tr>
                                <td>{{ $level->id_level }}</td>
                                <td>{{ $level->nama_level }}</td>
                                <td>{{ $level->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $level->updated_at->format('d M Y H:i') }}</td>
                                <td class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.levels.edit', $level->id_level) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.levels.destroy', $level->id_level) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus level ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data level.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
