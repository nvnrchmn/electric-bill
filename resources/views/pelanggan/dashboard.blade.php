@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('pelanggan.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold mb-6 text-base-content">Selamat Datang, {{ $user->username ?? 'Pelanggan' }}!</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card bg-primary text-primary-content shadow-md">
                    <div class="card-body">
                        <h2 class="text-lg font-bold">Tagihan Lunas</h2>
                        <p class="text-2xl">{{ $totalLunas }}</p>
                    </div>
                </div>

                <div class="card bg-error text-error-content shadow-md">
                    <div class="card-body">
                        <h2 class="text-lg font-bold">Tagihan Belum Lunas</h2>
                        <p class="text-2xl">{{ $totalBelumLunas }}</p>
                    </div>
                </div>

                <div class="card bg-success text-success-content shadow-md">
                    <div class="card-body">
                        <h2 class="text-lg font-bold">KWH Bulan Ini</h2>
                        <p class="text-2xl">{{ number_format($totalKwhBulanIni) }} KWH</p>
                    </div>
                </div>

                <div class="card bg-info text-info-content shadow-md">
                    <div class="card-body">
                        <h2 class="text-lg font-bold">Total Pembayaran Tahun Ini</h2>
                        <p class="text-2xl">Rp {{ number_format($totalPembayaranTahunIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <p class="text-base-content/80">Gunakan menu di sidebar untuk melihat detail penggunaan, tagihan, dan pembayaran
                Anda.</p>
        </div>
    </div>
@endsection
