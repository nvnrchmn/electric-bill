{{-- resources/views/admin/tagihans/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-base-content mb-6">Detail Tagihan</h1>

            <div class="bg-base-100 shadow-lg rounded-lg p-6 min-w-full mx-auto grid grid-cols-3 gap-10">
                <div class="mb-4">
                    <h2 class="text-2xl font-semibold text-primary mb-4">Informasi Tagihan</h2>
                    <p class="text-base-content mb-2"><strong>ID Tagihan:</strong> {{ $tagihan->id_tagihan }}</p>
                    <p class="text-base-content mb-2"><strong>Bulan / Tahun:</strong> {{ $tagihan->bulan }} /
                        {{ $tagihan->tahun }}</p>
                    <p class="text-base-content mb-2"><strong>Jumlah Meter Terpakai:</strong>
                        {{ number_format($tagihan->jumlah_meter) }} KWH</p>
                    <p class="text-base-content mb-2"><strong>Total Tagihan:</strong> Rp
                        {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</p>
                    <p class="text-base-content mb-2"><strong>Status:</strong>
                        @if ($tagihan->status == 'belum_dibayar')
                            <span class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                        @elseif($tagihan->status == 'sudah_dibayar')
                            <span class="badge badge-success">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                        @else
                            <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                        @endif
                    </p>
                    <p class="text-base-content mb-2"><strong>Dibuat Pada:</strong>
                        {{ $tagihan->created_at->format('d M Y H:i') }}</p>
                    <p class="text-base-content mb-2"><strong>Terakhir Diperbarui:</strong>
                        {{ $tagihan->updated_at->format('d M Y H:i') }}</p>
                </div>

                {{-- <div class="divider"></div> DaisyUI divider --}}

                <div class="mb-4">
                    <h2 class="text-2xl font-semibold text-primary mb-4">Informasi Pelanggan</h2>
                    <p class="text-base-content mb-2"><strong>Nama Pelanggan:</strong>
                        {{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                    <p class="text-base-content mb-2"><strong>Nomor KWH:</strong>
                        {{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</p>
                    <p class="text-base-content mb-2"><strong>Alamat:</strong> {{ $tagihan->pelanggan->alamat ?? 'N/A' }}
                    </p>
                    <p class="text-base-content mb-2"><strong>Tarif Golongan:</strong>
                        {{ $tagihan->pelanggan->tarif->golongan ?? 'N/A' }}</p>
                    <p class="text-base-content mb-2"><strong>Tarif per KWH:</strong> Rp
                        {{ number_format($tagihan->pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}</p>
                </div>

                {{-- <div class="divider"></div> --}}

                <div class="mb-4">
                    <h2 class="text-2xl font-semibold text-primary mb-4">Detail Penggunaan Terkait</h2>
                    @if ($tagihan->penggunaan)
                        <p class="text-base-content mb-2"><strong>Tanggal Periksa:</strong>
                            {{ \Carbon\Carbon::parse($tagihan->penggunaan->tanggal_periksa)->format('d M Y') }}</p>
                        <p class="text-base-content mb-2"><strong>Meter Awal:</strong>
                            {{ number_format($tagihan->penggunaan->meter_awal) }} KWH</p>
                        <p class="text-base-content mb-2"><strong>Meter Akhir:</strong>
                            {{ number_format($tagihan->penggunaan->meter_akhir) }} KWH</p>
                    @else
                        <p class="text-base-content">Detail penggunaan tidak ditemukan.</p>
                    @endif

                </div>
                <div></div>
                <div></div>
                <div class="flex justify-end">
                    <a href="{{ route('admin.tagihans.index') }}" class="btn btn-secondary">Kembali ke Daftar Tagihan</a>
                    {{-- Tombol aksi lain seperti "Bayar" bisa ditempatkan di sini --}}
                    @if ($tagihan->status == 'belum_dibayar')
                        <button class="btn btn-success">Bayar Tagihan</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
