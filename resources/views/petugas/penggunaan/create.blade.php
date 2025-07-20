{{-- resources/views/petugas/penggunaan/create.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Tambah Data Penggunaan Listrik</h1>

            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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

            <div class="card bg-base-300 shadow-xl p-6">
                <form action="{{ route('petugas.penggunaan.store') }}" method="POST">
                    @csrf

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Pelanggan:</span>
                        </label>
                        {{-- PASTIKAN NAME="id_pelanggan" DI SINI --}}
                        <select name="id_pelanggan"
                            class="select select-bordered w-full {{ $errors->has('id_pelanggan') ? 'select-error' : '' }}">
                            <option value="">Pilih Pelanggan</option>
                            @foreach ($pelanggans as $pelanggan)
                                {{-- PASTIKAN VALUE="{{ $pelanggan->id }}" DI SINI --}}
                                <option value="{{ $pelanggan->id_pelanggan }}"
                                    {{ old('id_pelanggan') == $pelanggan->id_pelanggan ? 'selected' : '' }}>
                                    {{ $pelanggan->nama_pelanggan }} ({{ $pelanggan->nomor_kwh }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_pelanggan')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">Bulan:</span>
                            </label>
                            <select name="bulan"
                                class="select select-bordered w-full {{ $errors->has('bulan') ? 'select-error' : '' }}">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                            @error('bulan')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">Tahun:</span>
                            </label>
                            <input type="number" name="tahun" placeholder="Tahun" value="{{ old('tahun', date('Y')) }}"
                                class="input input-bordered w-full {{ $errors->has('tahun') ? 'input-error' : '' }}"
                                min="2000" max="{{ date('Y') + 1 }}" />
                            @error('tahun')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">Meter Awal (KWH):</span>
                            </label>
                            <input type="number" name="meter_awal" placeholder="Meter Awal"
                                value="{{ old('meter_awal') }}"
                                class="input input-bordered w-full {{ $errors->has('meter_awal') ? 'input-error' : '' }}"
                                min="0" />
                            @error('meter_awal')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">Meter Akhir (KWH):</span>
                            </label>
                            <input type="number" name="meter_akhir" placeholder="Meter Akhir"
                                value="{{ old('meter_akhir') }}"
                                class="input input-bordered w-full {{ $errors->has('meter_akhir') ? 'input-error' : '' }}"
                                min="0" />
                            @error('meter_akhir')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Simpan Data Penggunaan</button>
                        <a href="{{ route('petugas.penggunaan.index') }}" class="btn btn-ghost">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
