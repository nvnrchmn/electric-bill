{{-- resources/views/petugas/tagihan/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold mb-6">Daftar Tagihan Pelanggan</h1>

            {{-- Filter Section (Optional, copy from penggunaan index if needed) --}}
            <div class="shadow-xl p-6 rounded-lg mb-6 bg-base-300">
                <form action="{{ route('petugas.tagihan.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bulan:</span>
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
                            <span class="label-text">Tahun:</span>
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
                    <a href="{{ route('petugas.tagihan.index') }}" class="btn btn-ghost">Reset Filter</a>
                </form>
            </div>

            <div class="overflow-x-auto rounded-lg shadow-xl bg-base-300">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Bulan/Tahun</th>
                            <th>Meter Penggunaan</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $tagihan)
                            <tr>
                                <td>{{ $loop->iteration + $tagihans->firstItem() - 1 }}</td>
                                <td>{{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::createFromDate($tagihan->tahun, $tagihan->bulan)->translatedFormat('F Y') }}
                                </td>
                                <td>{{ $tagihan->jumlah_meter }} KWH</td>
                                <td>Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge {{ ($tagihan->status == 'sudah_dibayar' ? 'badge-success' : $tagihan->status == 'dibatalkan' || $tagihan->status == 'terverifikasi') ? 'badge-success' : 'badge-warning' }}">
                                        {{ $tagihan->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('petugas.tagihan.show', $tagihan->id_tagihan) }}"
                                        class="btn btn-info btn-xs">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada tagihan yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tagihans->links() }}
            </div>
        </div>
    </div>
@endsection
