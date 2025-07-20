@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')
        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Detail Pelanggan: {{ $pelanggan->nama_pelanggan }}</h1>

            <div class="card shadow-xl min-w-full mx-auto grid grid-cols-2 gap-6">
                <div class="card-body">
                    {{-- ... existing details ... --}}
                    <div class="mb-4">
                        <p class="text-lg font-semibold">ID Pelanggan:</p>
                        <p>{{ $pelanggan->id_pelanggan }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Username:</p>
                        <p>{{ $pelanggan->username }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Nomor KWH:</p>
                        <p>{{ $pelanggan->nomor_kwh }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Nama Pelanggan:</p>
                        <p>{{ $pelanggan->nama_pelanggan }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Alamat:</p>
                        <p>{{ $pelanggan->alamat }}</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Daya / Tarif:</p>
                        <p>{{ $pelanggan->tarif->daya ?? 'Tidak Diketahui' }} (Rp
                            {{ number_format($pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}/KWH)</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">User Terkait:</p>
                        <p>{{ $pelanggan->user->username ?? 'Belum Ditentukan' }}
                            ({{ $pelanggan->user->level->nama_level ?? '' }})</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Dibuat Pada:</p>
                        <p>{{ $pelanggan->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Diperbarui Pada:</p>
                        <p>{{ $pelanggan->updated_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.pelanggans.edit', $pelanggan->id_pelanggan) }}"
                            class="btn btn-warning mr-2">Edit Pelanggan</a>
                        <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-ghost">Kembali ke Daftar
                            Pelanggan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
