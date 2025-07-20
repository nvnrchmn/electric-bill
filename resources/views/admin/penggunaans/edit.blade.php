@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Edit Data Penggunaan Listrik</h1>

            <div class="card shadow-xl max-w-lg mx-auto">
                <div class="card-body">
                    <form action="{{ route('admin.penggunaans.update', $penggunaan->id_penggunaan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-control mb-4">
                            <label class="label" for="id_pelanggan">
                                <span class="label-text">Pelanggan</span>
                            </label>
                            <select id="id_pelanggan" name="id_pelanggan"
                                class="select select-bordered w-full @error('id_pelanggan') select-error @enderror"
                                required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id_pelanggan }}"
                                        {{ old('id_pelanggan', $penggunaan->id_pelanggan) == $pelanggan->id_pelanggan ? 'selected' : '' }}>
                                        {{ $pelanggan->nama_pelanggan }} ({{ $pelanggan->nomor_kwh }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pelanggan')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="bulan">
                                <span class="label-text">Bulan Penggunaan</span>
                            </label>
                            <select id="bulan" name="bulan"
                                class="select select-bordered w-full @error('bulan') select-error @enderror" required>
                                <option value="">Pilih Bulan</option>
                                @php
                                    $months = [
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember',
                                    ];
                                @endphp
                                @foreach ($months as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('bulan', $penggunaan->bulan) == $key ? 'selected' : '' }}>{{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="tahun">
                                <span class="label-text">Tahun Penggunaan</span>
                            </label>
                            <select id="tahun" name="tahun"
                                class="select select-bordered w-full @error('tahun') select-error @enderror" required>
                                <option value="">Pilih Tahun</option>
                                @for ($i = date('Y') + 1; $i >= 2020; $i--)
                                    <option value="{{ $i }}"
                                        {{ old('tahun', $penggunaan->tahun) == $i ? 'selected' : '' }}>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('tahun')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="meter_awal">
                                <span class="label-text">Meter Awal (KWH)</span>
                            </label>
                            <input type="number" id="meter_awal" name="meter_awal" placeholder="Meter Awal"
                                class="input input-bordered w-full @error('meter_awal') input-error @enderror"
                                value="{{ old('meter_awal', $penggunaan->meter_awal) }}" required min="0" />
                            @error('meter_awal')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="meter_akhir">
                                <span class="label-text">Meter Akhir (KWH)</span>
                            </label>
                            <input type="number" id="meter_akhir" name="meter_akhir" placeholder="Meter Akhir"
                                class="input input-bordered w-full @error('meter_akhir') input-error @enderror"
                                value="{{ old('meter_akhir', $penggunaan->meter_akhir) }}" required min="0" />
                            @error('meter_akhir')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" class="btn btn-primary">Update Penggunaan</button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.penggunaans.index') }}" class="btn btn-ghost">Kembali ke Daftar
                            Penggunaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
