{{-- resources/views/pelanggan/penggunaan/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-200">
        {{-- Sidebar Pelanggan --}}
        @include('pelanggan.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Detail Penggunaan #{{ $penggunaan->id_penggunaan }}</h1>

            <div class="card bg-base-100 shadow-xl p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Informasi Penggunaan</h2>
                        <p><strong>ID Penggunaan:</strong> {{ $penggunaan->id_penggunaan }}</p>
                        <p><strong>Periode:</strong>
                            {{ \Carbon\Carbon::createFromDate($penggunaan->tahun, $penggunaan->bulan)->translatedFormat('F Y') }}
                        </p>
                        <p><strong>Tanggal Periksa:</strong>
                            {{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->translatedFormat('d F Y') }}</p>
                        <p><strong>Dicatat Oleh:</strong> {{ $penggunaan->user->username ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Detail Meteran</h2>
                        <p><strong>Meter Awal:</strong> {{ number_format($penggunaan->meter_awal) }} KWH</p>
                        <p><strong>Meter Akhir:</strong> {{ number_format($penggunaan->meter_akhir) }} KWH</p>
                        <p><strong>Total Penggunaan:</strong>
                            {{ number_format($penggunaan->meter_akhir - $penggunaan->meter_awal) }} KWH</p>
                    </div>
                </div>

                @if ($penggunaan->pelanggan && $penggunaan->pelanggan->tarif)
                    <div class="mt-6 border-t pt-4">
                        <h2 class="text-xl font-semibold mb-2">Informasi Tarif</h2>
                        <p><strong>Daya:</strong> {{ $penggunaan->pelanggan->tarif->daya ?? 0 }}</p>
                        <p><strong>Tarif per KWH:</strong> Rp
                            {{ number_format($penggunaan->pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}</p>
                    </div>
                @endif

                <div class="mt-6 text-right">
                    <a href="{{ route('pelanggan.penggunaan.index') }}" class="btn btn-secondary">Kembali ke Riwayat
                        Penggunaan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
