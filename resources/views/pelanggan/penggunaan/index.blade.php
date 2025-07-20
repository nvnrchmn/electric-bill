{{-- resources/views/pelanggan/penggunaan/index.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan dengan layout utama pelanggan Anda --}}

@section('content')
    <div class="flex h-screen bg-base-200">
        {{-- Sidebar Pelanggan --}}
        @include('pelanggan.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Riwayat Penggunaan Listrik Anda</h1>

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
            <div class="bg-base-100 shadow-xl p-6 rounded-lg mb-6">
                <form action="{{ route('pelanggan.penggunaan.index') }}" method="GET"
                    class="flex flex-wrap items-end gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bulan:</span>
                        </label>
                        <select name="bulan" class="select select-bordered">
                            @foreach ($months as $key => $month)
                                <option value="{{ $key }}"
                                    {{ (string) $bulanFilter === (string) $key ? 'selected' : '' }}>
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
                                <option value="{{ $key }}"
                                    {{ (string) $tahunFilter === (string) $key ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('pelanggan.penggunaan.index') }}" class="btn btn-ghost">Reset Filter</a>
                </form>
            </div>


            <div class="overflow-x-auto bg-base-100 rounded-lg shadow-xl">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Periode</th>
                            <th>Meter Awal</th>
                            <th>Meter Akhir</th>
                            <th>Total Penggunaan</th>
                            <th>Tanggal Periksa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunaans as $penggunaan)
                            <tr>
                                <td>#{{ $penggunaan->id_penggunaan }}</td>
                                <td>{{ \Carbon\Carbon::createFromDate($penggunaan->tahun, $penggunaan->bulan)->translatedFormat('F Y') }}
                                </td>
                                <td>{{ number_format($penggunaan->meter_awal) }} KWH</td>
                                <td>{{ number_format($penggunaan->meter_akhir) }} KWH</td>
                                <td>{{ number_format($penggunaan->meter_akhir - $penggunaan->meter_awal) }} KWH</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.penggunaan.show', $penggunaan->id_penggunaan) }}"
                                        class="btn btn-info btn-xs">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada riwayat penggunaan ditemukan untuk periode
                                    ini.</td>
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
