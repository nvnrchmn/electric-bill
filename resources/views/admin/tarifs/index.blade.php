@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold  mb-6">Manajemen Tarif Listrik</h1>

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
                <a href="{{ route('admin.tarifs.create') }}" class="btn btn-primary">Tambah Tarif Baru</a>
            </div>

            <div class="overflow-x-auto  shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Tarif</th>
                            <th>Daya</th>
                            <th>Tarif per KWH</th>
                            <th>Dibuat Pada</th>
                            <th>Diperbarui Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tarifs as $tarif)
                            <tr>
                                <td>{{ $tarif->id_tarif }}</td>
                                <td>{{ $tarif->daya }}</td>
                                <td>Rp {{ number_format($tarif->tarifperkwh, 2, ',', '.') }}</td>
                                <td>{{ $tarif->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $tarif->updated_at->format('d M Y H:i') }}</td>
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.tarifs.edit', $tarif->id_tarif) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.tarifs.destroy', $tarif->id_tarif) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tarif ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data tarif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
