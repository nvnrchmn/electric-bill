@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Data Penggunaan Listrik Pelanggan</h1>

            {{-- Alert untuk pesan sukses/error --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="alert alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('admin.penggunaans.create') }}" class="btn btn-primary">Tambah Penggunaan Baru</a>

                {{-- Filter Form --}}
                <form action="{{ route('admin.penggunaans.index') }}" method="GET" class="flex gap-2">
                    {{-- Filter Bulan --}}
                    <select name="bulan" class="select select-bordered">
                        <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        @foreach ($months as $key => $value)
                            <option value="{{ $key }}" {{ (int) $key === (int) $bulan ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Tahun --}}
                    <select name="tahun" class="select select-bordered">
                        <option value="all" {{ $tahun == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                        @foreach ($years as $year_option)
                            <option value="{{ $year_option }}" {{ $year_option == $tahun ? 'selected' : '' }}>
                                {{ $year_option }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Pelanggan --}}
                    <select name="pelanggan" class="select select-bordered">
                        <option value="all" {{ $pelangganFilter == 'all' ? 'selected' : '' }}>Semua Pelanggan</option>
                        @foreach ($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id_pelanggan }}"
                                {{ $pelangganFilter == $pelanggan->id_pelanggan ? 'selected' : '' }}>
                                {{ $pelanggan->nama_pelanggan }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-info">Filter</button>
                </form>

            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Nomor KWH</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Meter Awal</th>
                            <th>Meter Akhir</th>
                            <th>Tanggal Periksa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunaans as $penggunaan)
                            <tr>
                                <td>{{ $penggunaan->id_penggunaan }}</td>
                                <td>{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                <td>{{ $penggunaan->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                                <td>{{ $penggunaan->bulan }}</td>
                                <td>{{ $penggunaan->tahun }}</td>
                                <td>{{ number_format($penggunaan->meter_awal) }} KWH</td>
                                <td>{{ number_format($penggunaan->meter_akhir) }} KWH</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->format('d M Y') }}</td>
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.penggunaans.show', $penggunaan->id_penggunaan) }}"
                                        class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('admin.penggunaans.edit', $penggunaan->id_penggunaan) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    {{-- Tombol untuk membuat tagihan --}}
                                    @if ($penggunaan->tagihan)
                                        {{-- Cek apakah sudah ada tagihan terkait --}}
                                        <a href="{{ route('admin.tagihans.show', $penggunaan->tagihan->id_tagihan) }}"
                                            class="btn btn-sm btn-outline btn-info">Lihat Tagihan</a>
                                    @else
                                        <form
                                            action="{{ route('admin.penggunaans.generateTagihan', $penggunaan->id_penggunaan) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membuat tagihan untuk penggunaan ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Buat Tagihan</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.penggunaans.destroy', $penggunaan->id_penggunaan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penggunaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data penggunaan untuk bulan
                                    {{ $months[$bulan] ?? '-' }} tahun {{ $tahun }}.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
