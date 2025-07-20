{{-- resources/views/petugas/pembayaran/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold mb-6">Detail Pembayaran #{{ $pembayaran->id_pembayaran }}</h1>

            <div class="card shadow-xl p-6 mb-6 bg-base-300">
                <div class="grid grid-cols-3 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Informasi Pembayaran</h2>
                        <p><strong>ID Pembayaran:</strong> {{ $pembayaran->id_pembayaran }}</p>
                        <p><strong>Jumlah Uang Diterima:</strong> Rp
                            {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</p> {{-- SESUAIKAN --}}
                        <p><strong>Biaya Admin:</strong> Rp {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</p>
                        {{-- BARU --}}
                        <p><strong>Tanggal Pembayaran:</strong>
                            {{ \Carbon\Carbon::parse($pembayaran->create_at)->translatedFormat('d F Y H:i') }}</p>
                        <p><strong>Bulan Pembayaran:</strong>
                            {{ \Carbon\Carbon::createFromFormat('m', $pembayaran->bulan_bayar)->translatedFormat('F') ?? 'N/A' }}
                        </p> {{-- BARU --}}
                        <p><strong>Dicatat Oleh:</strong> {{ $pembayaran->user->username ?? 'N/A' }}</p>
                        <p><strong>Keterangan:</strong>
                            {{ old('keterangan', $pembayaran->keterangan) ?? ($pembayaran->keterangan ?? '-') }}</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Informasi Tagihan Terkait</h2>
                        <p><strong>ID Tagihan:</strong> {{ $pembayaran->tagihan->id_tagihan ?? 'N/A' }}</p>
                        <p><strong>Pelanggan:</strong> {{ $pembayaran->pelanggan->nama_pelanggan ?? 'N/A' }}
                            ({{ $pembayaran->pelanggan->nomor_kwh ?? 'N/A' }})</p> {{-- Langsung dari relasi pelanggan di pembayaran --}}
                        <p><strong>Periode Tagihan:</strong>
                            {{ \Carbon\Carbon::createFromDate($pembayaran->tagihan->tahun ?? null, $pembayaran->tagihan->bulan ?? null)->translatedFormat('F Y') ?? 'N/A' }}
                        </p>
                        <p><strong>Total Tagihan Asli:</strong> Rp
                            {{ number_format($pembayaran->tagihan->total_tagihan ?? 0, 2, ',', '.') }}</p>
                        {{-- SESUAIKAN --}}
                        <p><strong>Status Tagihan:</strong>
                            <span
                                class="badge {{ $pembayaran->tagihan->status == 'sudah_dibayar' ? 'badge-success' : ($pembayaran->tagihan->status == 'dibatalkan' ? 'badge-error' : 'badge-warning') }}">
                                {{ str_replace('_', ' ', Str::title($pembayaran->tagihan->status ?? 'N/A')) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <a href="{{ route('petugas.pembayaran.index') }}" class="btn btn-secondary">Kembali ke Riwayat
                        Pembayaran</a>
                </div>
            </div>
        </div>
    </div>
@endsection
