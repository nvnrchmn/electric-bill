{{-- resources/views/admin/laporan/pembayaran.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-base-content mb-6">Laporan Data Pembayaran</h1>

            {{-- Filter Form --}}
            <div class="mb-6">
                <form action="{{ route('admin.laporan.pembayaran') }}" method="GET" class="flex gap-2 items-center">
                    <select name="bulan" class="select select-bordered w-auto">
                        @foreach ($months as $key => $value)
                            <option value="{{ $key }}" {{ (string) $key === (string) $bulan ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="select select-bordered w-auto">
                        @foreach ($years as $key => $value)
                            <option value="{{ $value }}" {{ (string) $value === (string) $tahun ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-info">Filter</button>
                    {{-- Tombol Cetak/Download --}}
                    {{-- <a href="#" class="btn btn-primary ml-2">Cetak/PDF</a> --}}
                    {{-- <a href="#" class="btn btn-success ml-2">Download Excel</a> --}}
                </form>
            </div>

            <div class="overflow-x-auto bg-base-100 shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Pembayaran</th>
                            <th>ID Tagihan</th>
                            <th>No. KWH</th>
                            <th>Nama Pelanggan</th>
                            <th>Bulan/Tahun Tagihan</th>
                            <th>Tgl. Bayar</th>
                            <th>Biaya Admin</th>
                            <th>Total Bayar</th>
                            <th>Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanPembayaran as $pembayaran)
                            <tr>
                                <td>{{ $pembayaran->id_pembayaran }}</td>
                                <td>{{ $pembayaran->id_tagihan }}</td>
                                <td>{{ $pembayaran->tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                                <td>{{ $pembayaran->tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                @php
                                    $monthsMapping = [
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember',
                                    ];
                                    $bulanTagihan = $pembayaran->tagihan
                                        ? $monthsMapping[str_pad($pembayaran->tagihan->bulan, 2, '0', STR_PAD_LEFT)] ??
                                            $pembayaran->tagihan->bulan
                                        : 'N/A';
                                    $tahunTagihan = $pembayaran->tagihan ? $pembayaran->tagihan->tahun : 'N/A';
                                @endphp
                                <td>{{ $bulanTagihan }}/{{ $tahunTagihan }}</td>
                                <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->translatedFormat('d F Y') }}
                                </td>
                                <td>Rp {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</td>
                                <td>{{ $pembayaran->user->nama_admin ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-base-content">Tidak ada data pembayaran yang
                                    ditemukan untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
