@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-200">
        @include('pelanggan.sidebar')

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
            <div class="bg-base-100 shadow-xl p-6 rounded-lg mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Pelanggan:</span>
                        </label>
                        <select name="pelanggan_id" class="select select-bordered">
                            @foreach ($pelangganOptions as $id => $name)
                                <option value="{{ $id }}" {{ $filterPelanggan == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tahun:</span>
                        </label>
                        <select name="tahun" class="select select-bordered">
                            @foreach ($tahunOptions as $value => $label)
                                <option value="{{ $value }}" {{ $filterTahun == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('pelanggan.pembayaran.index') }}" class="btn btn-ghost">Reset Filter</a>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto bg-base-100 rounded-lg shadow-xl">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <th>Nama Pelanggan</th>
                            <th>Tagihan Bulan</th>
                            <th>Jumlah Bayar</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $bayar)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($bayar->created_at)->translatedFormat('d F Y H:i') }}</td>
                                <td>{{ $bayar->pelanggan->nama_pelanggan ?? '-' }}</td>
                                {{-- <td> --}}
                                {{-- {{ $bayar->tagihan->bulan ?? '-' }}/{{ $bayar->tagihan->tahun ?? '-' }} --}}
                                <td>{{ \Carbon\Carbon::createFromDate($bayar->tagihan->tahun, $bayar->tagihan->bulan)->translatedFormat('F Y') }}
                                    {{-- </td> --}}
                                <td>Rp {{ number_format($bayar->total_bayar, 0, ',', '.') }}</td>
                                <td>{{ $bayar->metode_pembayaran ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada riwayat pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pembayarans->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
