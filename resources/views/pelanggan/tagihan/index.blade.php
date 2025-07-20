{{-- resources/views/pelanggan/tagihan/index.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan dengan layout utama pelanggan Anda --}}

@section('content')
    <div class="flex h-screen bg-base-200">
        {{-- Sidebar Pelanggan --}}
        {{-- Anda bisa membuat sidebar terpisah untuk pelanggan atau menyesuaikan yang sudah ada --}}
        @include('pelanggan.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Riwayat Tagihan Anda</h1>

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
                <form action="{{ route('pelanggan.tagihan.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
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
                    <a href="{{ route('pelanggan.tagihan.index') }}" class="btn btn-ghost">Reset Filter</a>
                </form>
            </div>


            <div class="overflow-x-auto bg-base-100 rounded-lg shadow-xl">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID Tagihan</th>
                            <th>Periode</th>
                            <th>Penggunaan (KWH)</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $tagihan)
                            <tr>
                                <td>#{{ $tagihan->id_tagihan }}</td>
                                <td>{{ \Carbon\Carbon::createFromDate($tagihan->tahun, $tagihan->bulan)->translatedFormat('F Y') }}
                                </td>
                                <td>{{ number_format($tagihan->jumlah_meter) }} KWH</td>
                                <td>Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge {{ $tagihan->status == 'sudah_dibayar' ? 'badge-success' : ($tagihan->status == 'belum_dibayar' ? 'badge-warning' : 'badge-error') }}">
                                        {{ str_replace('_', ' ', Str::title($tagihan->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.tagihan.show', $tagihan->id_tagihan) }}"
                                        class="btn btn-info btn-xs">Detail</a>
                                    @if ($tagihan->status === 'belum_dibayar')
                                        {{-- Link atau tombol untuk proses pembayaran --}}
                                        {{-- Anda bisa menambahkan link ke halaman pembayaran, jika sudah ada --}}
                                        {{-- <a href="{{ route('pelanggan.pembayaran.create', ['tagihan_id' => $tagihan->id_tagihan]) }}" class="btn btn-success btn-xs ml-2">Bayar Sekarang</a> --}}
                                        {{-- Atau jika Anda punya instruksi pembayaran --}}
                                        {{-- <button class="btn btn-success btn-xs ml-2"
                                            onclick="showPaymentInstructions()">Bayar</button> --}}
                                        <button class="btn btn-success btn-xs ml-2"
                                            onclick="openPaymentModal({{ $tagihan->id_tagihan }})">Bayar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada tagihan ditemukan untuk periode ini.</td>
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

    {{-- Modal untuk instruksi pembayaran (contoh) --}}
    <dialog id="payment_instructions_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Instruksi Pembayaran</h3>
            <p class="py-4">Silakan lakukan pembayaran melalui transfer bank ke rekening berikut:</p>
            <p><strong>Bank:</strong> Bank XYZ</p>
            <p><strong>Nomor Rekening:</strong> 1234567890</p>
            <p><strong>Atas Nama:</strong> PT. Listrik Kita</p>
            <p class="mt-4">Setelah melakukan pembayaran, konfirmasi kepada petugas.</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Tutup</button>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function showPaymentInstructions() {
            document.getElementById('payment_instructions_modal').showModal();
        }
    </script>


    <dialog id="paymentModal" class="modal min-h-14">
        <form method="POST" id="paymentForm">
            @csrf
            <input type="hidden" name="tagihan_id" id="modalTagihanId">
            <div class="modal-box p-5">
                <h3 class="font-bold text-lg">Pilih Metode Pembayaran</h3>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Metode:</span>
                    </label>
                    <select name="metode_pembayaran" class="select select-bordered">
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
                    <button type="button" class="btn" onclick="closePaymentModal()">Batal</button>
                </div>
            </div>
        </form>
    </dialog>

    <script>
        function openPaymentModal(tagihanId) {
            document.getElementById('modalTagihanId').value = tagihanId;
            const form = document.getElementById('paymentForm');
            form.action = `/pelanggan/tagihan/${tagihanId}/bayar`;
            document.getElementById('paymentModal').showModal();
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').close();
        }
    </script>
@endsection
