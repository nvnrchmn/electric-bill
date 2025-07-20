{{-- resources/views/petugas/tagihan/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold mb-6">Detail Tagihan #{{ $tagihan->id_tagihan }}</h1>

            <div class="card shadow-xl p-6 mb-6 bg-base-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Informasi Tagihan</h2>
                        <p><strong>ID Tagihan:</strong> {{ $tagihan->id_tagihan }}</p>
                        <p><strong>Periode:</strong>
                            {{ \Carbon\Carbon::createFromDate($tagihan->tahun, $tagihan->bulan)->translatedFormat('F Y') }}
                        </p>
                        <p><strong>Jumlah Meter:</strong> {{ $tagihan->jumlah_meter }} KWH</p>
                        <p><strong>Total Tagihan:</strong> Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge {{ $tagihan->status == 'sudah_dibayar' || $tagihan->status == 'terverifikasi' ? 'badge-success' : 'badge-warning' }}">
                                {{ $tagihan->status }}
                            </span>
                        </p>
                        <p><strong>Tanggal Dibuat:</strong>
                            {{ \Carbon\Carbon::parse($tagihan->created_at)->translatedFormat('d F Y H:i') }}</p>
                        <p><strong>Terakhir Diperbarui:</strong>
                            {{ \Carbon\Carbon::parse($tagihan->updated_at)->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Informasi Pelanggan</h2>
                        <p><strong>Nama Pelanggan:</strong> {{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                        <p><strong>Nomor KWH:</strong> {{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</p>
                        <p><strong>Alamat:</strong> {{ $tagihan->pelanggan->alamat ?? 'N/A' }}</p>
                        <p><strong>Daya:</strong> {{ $tagihan->pelanggan->tarif->daya ?? 'N/A' }} VA</p>
                        <p><strong>Tarif Per KWH:</strong> Rp
                            {{ number_format($tagihan->pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-2">Detail Penggunaan Listrik</h2>
                    <p><strong>Meter Awal:</strong> {{ $tagihan->penggunaan->meter_awal ?? 'N/A' }} KWH</p>
                    <p><strong>Meter Akhir:</strong> {{ $tagihan->penggunaan->meter_akhir ?? 'N/A' }} KWH</p>
                    <p><strong>Tanggal Periksa:</strong>
                        {{ \Carbon\Carbon::parse($tagihan->penggunaan->tanggal_periksa ?? null)->translatedFormat('d F Y') ?? 'N/A' }}
                    </p>
                    <p><strong>Dicatat Oleh:</strong> {{ $tagihan->penggunaan->petugas->username ?? 'N/A' }}</p>
                </div>

                <div class="mt-6 text-right">
                    <a href="{{ route('petugas.tagihan.index') }}" class="btn btn-secondary">Kembali ke Daftar Tagihan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
