{{-- resources/views/admin/pembayarans/create.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-base-content mb-6">Catat Pembayaran Tagihan</h1>

            {{-- Alerts --}}
            @if (session('success'))
                <div role="alert" class="alert alert-success mb-4"><span>{{ session('success') }}</span></div>
            @endif
            @if (session('error'))
                <div role="alert" class="alert alert-error mb-4"><span>{{ session('error') }}</span></div>
            @endif
            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-base-100 shadow-lg rounded-lg p-6 max-w-lg mx-auto">
                <form action="{{ route('admin.tagihans.storePembayaran', $tagihan->id_tagihan) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">ID Tagihan</span>
                        </label>
                        <input type="text" value="{{ $tagihan->id_tagihan }}" class="input input-bordered w-full"
                            readonly />
                    </div>

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">Nama Pelanggan</span>
                        </label>
                        <input type="text" value="{{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}"
                            class="input input-bordered w-full" readonly />
                    </div>

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">Nomor KWH</span>
                        </label>
                        <input type="text" value="{{ $tagihan->pelanggan->nomor_kwh ?? 'N/A' }}"
                            class="input input-bordered w-full" readonly />
                    </div>

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">Bulan/Tahun Tagihan</span>
                        </label>
                        @php
                            // Untuk memastikan bulan tampil sebagai nama bulan
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
                            $bulanTagihan = $months[str_pad($tagihan->bulan, 2, '0', STR_PAD_LEFT)] ?? $tagihan->bulan;
                        @endphp
                        <input type="text" value="{{ $bulanTagihan }}/{{ $tagihan->tahun }}"
                            class="input input-bordered w-full" readonly />
                    </div>

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">Total Tagihan (Rp)</span>
                        </label>
                        <input type="text" value="Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}"
                            class="input input-bordered w-full" readonly />
                        <input type="hidden" name="tagihan_id" value="{{ $tagihan->id_tagihan }}"> {{-- Hidden field untuk ID tagihan --}}
                    </div>

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">Biaya Admin (Rp)</span>
                        </label>
                        <input type="number" name="biaya_admin" value="{{ old('biaya_admin', $biayaAdminDefault) }}"
                            step="0.01" min="0"
                            class="input input-bordered w-full @error('biaya_admin') input-error @enderror" />
                        @error('biaya_admin')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text">Total Tagihan Pelanggan (Rp)</span>
                            <span class="label-text-alt text-warning">Minimal: Rp
                                {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</span>
                        </label>
                        <input type="number" name="total_bayar"
                            value="{{ old('total_bayar', $tagihan->total_tagihan + $biayaAdminDefault) }}" step="0.01"
                            min="{{ $tagihan->total_tagihan }}"
                            class="input input-bordered w-full @error('total_bayar') input-error @enderror" />
                        @error('total_bayar')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('admin.tagihans.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-success">Catat Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
