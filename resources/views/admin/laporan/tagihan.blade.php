{{-- resources/views/admin/laporan/tagihan.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-base-content mb-6">Laporan Data Tagihan</h1>

            {{-- Filter Form --}}
            <div class="mb-6">
                <form action="{{ route('admin.laporan.tagihan') }}" method="GET" class="flex gap-2 items-center">
                    <select name="bulan" class="select select-bordered w-auto">
                        @foreach ($months as $key => $value)
                            <option value="{{ $key }}" {{ (string) $key === (string) $bulan ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="select select-bordered w-auto">
                        {{-- PASTIkan menggunakan $key sebagai value dan $value sebagai teks --}}
                        @foreach ($years as $key => $value)
                            <option value="{{ $key }}" {{ (string) $key === (string) $tahun ? 'selected' : '' }}>
                                {{ $value }}</option>
                            {{-- {{ dd($years) }} --}}
                        @endforeach
                    </select>
                    <select name="status" class="select select-bordered w-auto">
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ (string) $key === (string) $status ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-info">Filter</button>
                    {{-- ... (Tombol Cetak/Download) ... --}}
                </form>
            </div>

            <div class="overflow-x-auto bg-base-100 shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Tagihan</th>
                            <th>No. KWH</th>
                            <th>Nama Pelanggan</th>
                            <th>Bulan/Tahun Tagihan</th>
                            <th>Jml. Meter</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanTagihan as $tagihan)
                            <tr>
                                <td>{{ $tagihan->id_tagihan }}</td>
                                <td>{{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                                <td>{{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                @php
                                    $bulanNama =
                                        $months[str_pad($tagihan->bulan, 2, '0', STR_PAD_LEFT)] ?? $tagihan->bulan;
                                @endphp
                                <td>{{ $bulanNama }}/{{ $tagihan->tahun }}</td>
                                <td>{{ number_format($tagihan->jumlah_meter) }} KWH</td>
                                <td>Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</td>
                                <td>
                                    @if ($tagihan->status == 'belum_dibayar')
                                        <span
                                            class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                                    @elseif($tagihan->status == 'sudah_dibayar')
                                        <span
                                            class="badge badge-success">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                                    @else
                                        <span
                                            class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-base-content">Tidak ada data tagihan yang
                                    ditemukan untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
