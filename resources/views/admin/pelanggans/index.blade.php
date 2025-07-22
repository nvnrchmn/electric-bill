@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')
        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Manajemen Pelanggan</h1>

            {{-- Alerts --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-4">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="alert alert-error mb-4">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('admin.pelanggans.create') }}" class="btn btn-primary">Tambah Pelanggan Baru</a>

                {{-- Search Form --}}
                <form action="{{ route('admin.pelanggans.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}"
                        class="input input-bordered" />
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Pelanggan</th>
                            <th>Username</th>
                            <th>Nomor KWH</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>Daya (Tarif)</th>
                            <th>User Terkait</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggans as $pelanggan)
                            <tr>
                                <td>{{ $pelanggan->id_pelanggan }}</td>
                                <td>{{ $pelanggan->username }}</td>
                                <td>{{ $pelanggan->nomor_kwh }}</td>
                                <td>{{ $pelanggan->nama_pelanggan }}</td>
                                <td>{{ $pelanggan->alamat }}</td>
                                <td>{{ $pelanggan->tarif->daya ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $pelanggan->user->username ?? 'Belum Ditentukan' }}</td>
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.pelanggans.show', $pelanggan->id_pelanggan) }}"
                                        class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('admin.pelanggans.edit', $pelanggan->id_pelanggan) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.pelanggans.destroy', $pelanggan->id_pelanggan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $pelanggans->appends(['search' => request('search')])->links() }}
            </div>

        </div>
    </div>
@endsection
