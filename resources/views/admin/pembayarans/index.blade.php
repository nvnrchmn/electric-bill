{{-- resources/views/admin/pembayarans/index.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Data Pembayaran Tagihan</h1>

            {{-- Alerts --}}
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
            @if (session('info'))
                <div role="alert" class="alert alert-info mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            {{-- Filter Form --}}
            <div class="mb-6">
                <form action="{{ route('admin.pembayarans.index') }}" method="GET" class="flex gap-2 items-center">
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
                </form>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Pembayaran</th>
                            <th>ID Tagihan</th>
                            <th>Pelanggan</th>
                            <th>No. KWH</th>
                            <th>Tgl. Bayar</th>
                            <th>Bulan Tagihan</th>
                            <th>Biaya Admin</th>
                            <th>Total Bayar</th>
                            <th>Dicatat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $pembayaran)
                            <tr>
                                <td>{{ $pembayaran->id_pembayaran }}</td>
                                <td>{{ $pembayaran->id_tagihan }}</td>
                                <td>{{ $pembayaran->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                <td>{{ $pembayaran->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{ $months[str_pad($pembayaran->tagihan->bulan, 2, '0', STR_PAD_LEFT)] ?? $pembayaran->tagihan->bulan }}/{{ $pembayaran->tagihan->tahun }}
                                </td>
                                <td>Rp {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</td>
                                <td>{{ $pembayaran->user->nama_admin ?? 'System' }}</td>
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.pembayarans.show', $pembayaran->id_pembayaran) }}"
                                        class="btn btn-sm btn-info">Detail</a>
                                    {{-- Tombol Hapus (hati-hati dengan penghapusan pembayaran) --}}
                                    {{-- <form action="{{ route('admin.pembayarans.destroy', $pembayaran->id_pembayaran) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini? Tindakan ini tidak disarankan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-base-content">Tidak ada data pembayaran yang
                                    ditemukan untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
