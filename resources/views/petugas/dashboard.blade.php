{{-- resources/views/petugas/dashboard.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan ini dengan layout utama Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">

        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar') {{-- Pastikan path ini benar --}}
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-8">Selamat Datang, {{ $user->name ?? 'Petugas' }}!</h1>
            <p class="text-lg text-base-content/80 mb-6">Ini adalah dashboard Anda. Anda dapat melihat ringkasan tugas dan
                statistik relevan di sini.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {{-- Card: Penggunaan Dicatat Bulan Ini --}}
                <div class="card bg-base-300 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-primary">Penggunaan Dicatat Bulan Ini</h2>
                        <p class="text-4xl font-extrabold">{{ $penggunaanBulanIni }}</p> {{-- Data dari controller --}}
                        <p class="text-sm text-base-content/70">Data yang Anda catat di bulan ini.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('petugas.penggunaan.index') }}"
                                class="btn btn-sm btn-primary btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                {{-- Card: Pembayaran Diverifikasi Bulan Ini --}}
                <div class="card bg-base-300 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-secondary">Pembayaran Dicatat Bulan Ini</h2> {{-- Sesuaikan teks --}}
                        <p class="text-4xl font-extrabold">{{ $pembayaranBulanIni }}</p> {{-- Data dari controller --}}
                        <p class="text-sm text-base-content/70">Pembayaran yang Anda catat/verifikasi di bulan ini.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('petugas.pembayaran.index') }}"
                                class="btn btn-sm btn-secondary btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                {{-- Card: Total Tagihan Belum Dibayar --}}
                <div class="card bg-base-300 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-accent">Total Tagihan Belum Dibayar</h2>
                        <p class="text-4xl font-extrabold">{{ $totalTagihanBelumDibayar }}</p> {{-- Data dari controller --}}
                        <p class="text-sm text-base-content/70">Jumlah tagihan yang belum lunas (seluruh sistem).</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('petugas.tagihan.index') }}" class="btn btn-sm btn-accent btn-outline">Lihat
                                Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian untuk pengumuman atau tugas penting (Opsional) --}}
            <div class="card bg-base-300 shadow-xl p-6 mt-8">
                <h2 class="card-title text-base-content mb-4 border-b pb-2">Informasi & Tugas Penting</h2>
                <ul class="list-disc list-inside text-base-content/80">
                    <li>Segera periksa data penggunaan untuk bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }} yang
                        belum tercatat.</li>
                    <li>Ada {{ $totalTagihanBelumDibayar }} tagihan yang menunggu pembayaran.</li>
                    <li>Pastikan data pelanggan selalu akurat.</li>
                </ul>
                <p class="text-sm text-base-content/60 mt-4">Informasi ini akan diperbarui secara otomatis.</p>
            </div>

        </div>
    </div>
@endsection
