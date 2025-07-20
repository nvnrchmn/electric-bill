{{-- resources/views/petugas/pembayaran/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Riwayat Pembayaran</h1>

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

            {{-- Filter Section --}}
            <div class="bg-base-300 shadow-xl p-6 rounded-lg mb-6">
                <form action="{{ route('petugas.pembayaran.index') }}" method="GET"
                    class="flex flex-wrap items-end gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bulan Pembayaran:</span> {{-- Label disesuaikan --}}
                        </label>
                        <select name="bulan" class="select select-bordered">
                            @foreach ($months as $key => $month)
                                <option value="{{ $key }}" {{ $bulanFilter == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tahun Pembayaran:</span> {{-- Label disesuaikan --}}
                        </label>
                        <select name="tahun" class="select select-bordered">
                            @foreach ($years as $key => $year)
                                <option value="{{ $key }}" {{ $tahunFilter == $key ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('petugas.pembayaran.index') }}" class="btn btn-ghost">Reset Filter</a>
                </form>
            </div>

            <div class="mb-6">
                <a href="{{ route('petugas.pembayaran.create') }}" class="btn btn-success">Catat Pembayaran Baru</a>
            </div>

            <div class="overflow-x-auto bg-base-300 rounded-lg shadow-xl">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>No. KWH</th>
                            <th>Tagihan Periode</th>
                            <th>Total Bayar</th> {{-- SESUAIKAN: Jumlah Bayar -> Total Bayar --}}
                            <th>Biaya Admin</th> {{-- BARU --}}
                            <th>Tanggal Bayar</th>
                            <th>Dicatat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $pembayaran)
                            <tr>
                                <td>{{ $loop->iteration + $pembayarans->firstItem() - 1 }}</td>
                                <td>{{ $pembayaran->pelanggan->nama_pelanggan ?? 'N/A' }}</td> {{-- Langsung dari relasi pelanggan di pembayaran --}}
                                <td>{{ $pembayaran->pelanggan->nomor_kwh ?? 'N/A' }}</td> {{-- Langsung dari relasi pelanggan di pembayaran --}}
                                <td>{{ \Carbon\Carbon::createFromDate($pembayaran->tagihan->tahun ?? null, $pembayaran->tagihan->bulan ?? null)->translatedFormat('F Y') ?? 'N/A' }}
                                </td>
                                <td>Rp {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</td>
                                {{-- SESUAIKAN --}}
                                <td>Rp {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</td>
                                {{-- BARU --}}
                                <td>{{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y H:i') }}
                                </td>
                                <td>{{ $pembayaran->user->username ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('petugas.pembayaran.show', $pembayaran->id_pembayaran) }}"
                                        class="btn btn-info btn-xs">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada pembayaran yang dicatat.</td>
                                {{-- Sesuaikan colspan --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pembayarans->links() }}
            </div>
        </div>
    </div>
@endsection
