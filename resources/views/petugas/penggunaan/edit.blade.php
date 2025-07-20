{{-- resources/views/petugas/penggunaan/edit.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Edit Data Penggunaan Listrik</h1>

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
                <form action="{{ route('petugas.penggunaan.update', $penggunaan->id_penggunaan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Pelanggan:</span>
                        </label>
                        {{-- Pelanggan tidak bisa diubah setelah penggunaan dibuat --}}
                        <input type="text"
                            value="{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }} ({{ $penggunaan->pelanggan->nomor_kwh ?? 'N/A' }})"
                            class="input input-bordered w-full bg-base-200" disabled />
                        <input type="hidden" name="id_pelanggan" value="{{ $penggunaan->id_pelanggan }}">
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
                                        {{ old('bulan', $penggunaan->bulan) == $i ? 'selected' : '' }}>
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
                            <input type="number" name="tahun" placeholder="Tahun"
                                value="{{ old('tahun', $penggunaan->tahun) }}"
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
                                value="{{ old('meter_awal', $penggunaan->meter_awal) }}"
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
                                value="{{ old('meter_akhir', $penggunaan->meter_akhir) }}"
                                class="input input-bordered w-full {{ $errors->has('meter_akhir') ? 'input-error' : '' }}"
                                min="0" />
                            @error('meter_akhir')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Update Data Penggunaan</button>
                        <a href="{{ route('petugas.penggunaan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
