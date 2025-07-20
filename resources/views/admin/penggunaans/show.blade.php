@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Detail Penggunaan Listrik</h1>

            <div class="card shadow-xl min-w-full mx-auto grid grid-cols-2 gap-6">
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">ID Penggunaan:</p>
                        <p>{{ $penggunaan->id_penggunaan }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Pelanggan:</p>
                        <p>{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }}
                            ({{ $penggunaan->pelanggan->nomor_kwh ?? 'N/A' }})</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Daya (Tarif):</p>
                        <p>{{ $penggunaan->pelanggan->tarif->daya ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Bulan Penggunaan:</p>
                        <p>{{ $penggunaan->bulan }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Tahun Penggunaan:</p>
                        <p>{{ $penggunaan->tahun }}</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Tanggal Periksa:</p>
                        <p>{{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->format('d F Y') }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Meter Awal (KWH):</p>
                        <p>{{ number_format($penggunaan->meter_awal) }} KWH</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Meter Akhir (KWH):</p>
                        <p>{{ number_format($penggunaan->meter_akhir) }} KWH</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Total Penggunaan (KWH):</p>
                        <p>{{ number_format($penggunaan->meter_akhir - $penggunaan->meter_awal) }} KWH</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Dibuat Pada:</p>
                        <p>{{ $penggunaan->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Diperbarui Pada:</p>
                        <p>{{ $penggunaan->updated_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.penggunaans.edit', $penggunaan->id_penggunaan) }}"
                            class="btn btn-warning mr-2">Edit Penggunaan</a>
                        <a href="{{ route('admin.penggunaans.index') }}" class="btn btn-ghost">Kembali ke Daftar
                            Penggunaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
