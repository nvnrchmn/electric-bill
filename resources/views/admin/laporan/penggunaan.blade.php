{{-- resources/views/admin/laporan/penggunaan.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-base-content mb-6">Laporan Data Penggunaan Listrik</h1>

            {{-- Filter Form --}}
            <div class="mb-6">
                <div class="mb-6">
                    <form action="{{ route('admin.laporan.penggunaan') }}" method="GET" class="flex gap-2 items-center">
                        <select name="bulan" class="select select-bordered w-auto">
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}"
                                    {{ (string) $key === (string) $bulan ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        <select name="tahun" class="select select-bordered w-auto">
                            {{-- INI YANG HARUS PASTI SANGAT BENAR: value="{{ $key }}" --}}
                            @foreach ($years as $key => $value)
                                <option value="{{ $key }}"
                                    {{ (string) $key === (string) $tahun ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-info">Filter</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto bg-base-100 shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Penggunaan</th>
                            <th>No. KWH</th>
                            <th>Nama Pelanggan</th>
                            <th>Bulan/Tahun</th>
                            <th>Tgl. Periksa</th>
                            <th>Meter Awal</th>
                            <th>Meter Akhir</th>
                            <th>Pemakaian (KWH)</th>
                            <th>Daya</th>
                            <th>Tarif/KWH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanPenggunaan as $penggunaan)
                            <tr>
                                <td>{{ $penggunaan->id_penggunaan }}</td>
                                <td>{{ $penggunaan->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                                <td>{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                @php
                                    $bulanNama =
                                        $months[str_pad($penggunaan->bulan, 2, '0', STR_PAD_LEFT)] ??
                                        $penggunaan->bulan;
                                @endphp
                                <td>{{ $bulanNama }}/{{ $penggunaan->tahun }}</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{ number_format($penggunaan->meter_awal) }}</td>
                                <td>{{ number_format($penggunaan->meter_akhir) }}</td>
                                <td>{{ number_format($penggunaan->meter_akhir - $penggunaan->meter_awal) }}</td>
                                <td>{{ $penggunaan->pelanggan->tarif->daya ?? 'N/A' }} VA</td>
                                <td>Rp {{ number_format($penggunaan->pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-base-content">Tidak ada data penggunaan yang
                                    ditemukan untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
