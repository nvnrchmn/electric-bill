{{-- resources/views/admin/tagihans/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100"> {{-- Gunakan kelas DaisyUI untuk background --}}
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Data Tagihan Pelanggan</h1>

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
                <form action="{{ route('admin.tagihans.index') }}" method="GET" class="flex gap-2 items-center">
                    <select name="bulan" class="select select-bordered w-auto">
                        @foreach ($months as $key => $value)
                            <option value="{{ $key }}" {{ (string) $key === (string) $bulan ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="select select-bordered w-auto">
                        @foreach ($years as $key => $value)
                            {{-- Perhatikan bahwa $years adalah collection, jadi $key adalah index --}}
                            <option value="{{ $value }}" {{ (string) $value === (string) $tahun ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="select select-bordered w-auto">
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}"
                                {{ (string) $key === (string) $status ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-info">Filter</button>
                </form>
            </div>

            <div class="overflow-x-auto shadow-lg rounded-lg"> {{-- bg-base-100 --}}
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Tagihan</th>
                            <th>Pelanggan</th>
                            <th>No. KWH</th>
                            <th>Bulan/Tahun</th>
                            <th>Jumlah Meter (KWH)</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $tagihan)
                            <tr>
                                <td>{{ $tagihan->id_tagihan }}</td>
                                <td>{{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                <td>{{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}</td>
                                {{-- <td>{{ $months[$tagihan->bulan] ?? $tagihan->bulan }}/{{ $tagihan->tahun }}</td> --}}
                                {{-- <td>{{ $months[$tagihan->bulan] ?? $tagihan->bulan }}/{{ $tagihan->tahun }}</td> --}}
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
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.tagihans.show', $tagihan->id_tagihan) }}"
                                        class="btn btn-sm btn-info">Detail</a>
                                    {{-- Tombol Pembayaran (akan diimplementasikan nanti) --}}
                                    @if ($tagihan->status == 'belum_dibayar')
                                        {{-- <button class="btn btn-sm btn-success">Bayar</button> --}}
                                    @endif
                                    <form action="{{ route('admin.tagihans.destroy', $tagihan->id_tagihan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-base-content">Tidak ada data tagihan yang
                                    ditemukan untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
