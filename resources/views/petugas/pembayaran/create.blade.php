{{-- resources/views/petugas/pembayaran/create.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Catat Pembayaran Baru</h1>

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

            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card bg-base-300 shadow-xl p-6">
                <form action="{{ route('petugas.pembayaran.store') }}" method="POST">
                    @csrf

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Pilih Tagihan (Status: Belum Dibayar):</span>
                        </label>
                        <select name="id_tagihan" id="id_tagihan"
                            class="select select-bordered w-full {{ $errors->has('id_tagihan') ? 'select-error' : '' }}">
                            <option value="">-- Pilih Tagihan --</option>
                            @foreach ($tagihans as $tagihan)
                                <option value="{{ $tagihan->id_tagihan }}"
                                    data-total-tagihan="{{ $tagihan->total_tagihan }}" {{-- Ambil total_tagihan --}}
                                    {{ old('id_tagihan') == $tagihan->id_tagihan || (isset($selectedTagihan) && $selectedTagihan->id_tagihan == $tagihan->id_tagihan) ? 'selected' : '' }}>
                                    Tagihan #{{ $tagihan->id_tagihan }} -
                                    {{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}
                                    ({{ \Carbon\Carbon::createFromDate($tagihan->tahun, $tagihan->bulan)->translatedFormat('F Y') }})
                                    - Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tagihan')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Total Tagihan (Dari Tagihan):</span>
                        </label>
                        <input type="text" id="tagihan_total_tagihan" class="input input-bordered w-full" readonly
                            value="{{ old('id_tagihan') ? number_format($selectedTagihan->total_tagihan ?? 0, 2, ',', '.') : '' }}" />
                        <span class="label-text-alt text-info">Ini adalah jumlah tagihan yang belum dibayar.</span>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Biaya Admin:</span>
                        </label>
                        <input type="number" name="biaya_admin" id="biaya_admin"
                            placeholder="Masukkan biaya admin (misal: 2500)"
                            class="input input-bordered w-full {{ $errors->has('biaya_admin') ? 'input-error' : '' }}"
                            value="{{ old('biaya_admin', 0) }}" step="0.01" min="0">
                        @error('biaya_admin')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Total yang Harus Dibayar (Tagihan + Admin):</span>
                        </label>
                        <input type="text" id="total_harus_dibayar" class="input input-bordered w-full" readonly
                            value="{{ old('id_tagihan') ? number_format(($selectedTagihan->total_tagihan ?? 0) + old('biaya_admin', 0), 2, ',', '.') : '' }}" />
                        <span class="label-text-alt text-info">Ini adalah total yang harus dibayar oleh pelanggan.</span>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Jumlah Uang Pembayaran (Aktual Diterima):</span>
                        </label>
                        <input type="number" name="jumlah_uang_dibayar"
                            placeholder="Masukkan jumlah uang yang diterima dari pelanggan"
                            class="input input-bordered w-full {{ $errors->has('jumlah_uang_dibayar') ? 'input-error' : '' }}"
                            value="{{ old('jumlah_uang_dibayar') }}" step="0.01" min="0">
                        @error('jumlah_uang_dibayar')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Keterangan (Opsional):</span>
                        </label>
                        <textarea name="keterangan" placeholder="Catatan tambahan tentang pembayaran"
                            class="textarea textarea-bordered h-24 w-full {{ $errors->has('keterangan') ? 'textarea-error' : '' }}">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Catat Pembayaran</button>
                        <a href="{{ route('petugas.pembayaran.index') }}" class="btn btn-ghost ">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tagihanSelect = document.getElementById('id_tagihan');
            const tagihanTotalTagihanInput = document.getElementById('tagihan_total_tagihan');
            const biayaAdminInput = document.getElementById('biaya_admin');
            const totalHarusDibayarInput = document.getElementById('total_harus_dibayar');

            function calculateTotal() {
                const selectedOption = tagihanSelect.options[tagihanSelect.selectedIndex];
                const totalTagihanValue = parseFloat(selectedOption.dataset.totalTagihan || 0);
                const biayaAdminValue = parseFloat(biayaAdminInput.value || 0);

                const totalHarusDibayar = totalTagihanValue + biayaAdminValue;

                tagihanTotalTagihanInput.value = totalTagihanValue.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                totalHarusDibayarInput.value = totalHarusDibayar.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            tagihanSelect.addEventListener('change', calculateTotal);
            biayaAdminInput.addEventListener('input', calculateTotal); // Update on admin fee change

            // Trigger calculation on load if an option is pre-selected (e.g., old input or selectedTagihan)
            if (tagihanSelect.value) {
                calculateTotal();
            }
        });
    </script>
@endsection
