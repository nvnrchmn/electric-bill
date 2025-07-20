{{-- resources/views/petugas/penggunaan/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Manajemen Penggunaan Listrik</h1>

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

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('petugas.penggunaan.create') }}" class="btn btn-primary">Tambah Penggunaan Baru</a>
            </div>

            {{-- Filter Form --}}
            <div class="mb-6 card bg-base-300 shadow-xl p-4">
                <form action="{{ route('petugas.penggunaan.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bulan:</span>
                        </label>
                        <select name="bulan" class="select select-bordered w-auto">
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}"
                                    {{ (string) $key === (string) $bulanFilter ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tahun:</span>
                        </label>
                        <select name="tahun" class="select select-bordered w-auto">
                            @foreach ($years as $key => $value)
                                <option value="{{ $key }}"
                                    {{ (string) $key === (string) $tahunFilter ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control"> {{-- Adjust margin-top to align with selects --}}
                        <button type="submit" class="btn btn-info">Filter</button>
                    </div>
                    @if ($bulanFilter !== 'all' || $tahunFilter !== 'all')
                        <div class="form-control">
                            <a href="{{ route('petugas.penggunaan.index') }}" class="btn btn-ghost">Reset Filter</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto bg-base-300 rounded-lg shadow-xl">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Bulan/Tahun</th>
                            <th>Meter Awal</th>
                            <th>Meter Akhir</th>
                            <th>Tanggal Periksa</th>
                            <th>Dicatat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunaans as $penggunaan)
                            <tr>
                                <td>{{ $loop->iteration + $penggunaans->firstItem() - 1 }}</td>
                                <td>{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::createFromDate($penggunaan->tahun, $penggunaan->bulan)->translatedFormat('F Y') }}
                                </td>
                                <td>{{ $penggunaan->meter_awal }} KWH</td>
                                <td>{{ $penggunaan->meter_akhir }} KWH</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{ $penggunaan->petugas->username ?? 'N/A' }}</td>
                                {{-- <p>{{ dd($penggunaan->petugas->username) }}</p> --}}
                                <td class="flex gap-2">
                                    <a href="{{ route('petugas.penggunaan.show', $penggunaan->id_penggunaan) }}"
                                        class="btn btn-info btn-xs">Detail</a>
                                    <a href="{{ route('petugas.penggunaan.edit', $penggunaan->id_penggunaan) }}"
                                        class="btn btn-warning btn-xs">Edit</a>
                                    <form action="{{ route('petugas.penggunaan.destroy', $penggunaan->id_penggunaan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data penggunaan yang dicatat oleh Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $penggunaans->links() }}
            </div>
        </div>
    </div>
@endsection
