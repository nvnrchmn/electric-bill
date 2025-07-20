{{-- resources/views/pelanggan/tagihan/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-200">
        {{-- Sidebar Pelanggan --}}
        @include('pelanggan.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Detail Tagihan #{{ $tagihan->id_tagihan }}</h1>

            <div class="card bg-base-100 shadow-xl p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Informasi Tagihan</h2>
                        <p><strong>ID Tagihan:</strong> {{ $tagihan->id_tagihan }}</p>
                        <p><strong>Periode:</strong>
                            {{ \Carbon\Carbon::createFromDate($tagihan->tahun, $tagihan->bulan)->translatedFormat('F Y') }}
                        </p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge {{ $tagihan->status == 'sudah_dibayar' ? 'badge-success' : ($tagihan->status == 'belum_dibayar' ? 'badge-warning' : 'badge-error') }}">
                                {{ str_replace('_', ' ', Str::title($tagihan->status)) }}
                            </span>
                        </p>
                        <p><strong>Total Tagihan:</strong> Rp {{ number_format($tagihan->total_tagihan, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Detail Penggunaan</h2>
                        @if ($tagihan->penggunaan)
                            <p><strong>ID Penggunaan:</strong> {{ $tagihan->penggunaan->id_penggunaan }}</p>
                            <p><strong>Meter Awal:</strong> {{ number_format($tagihan->penggunaan->meter_awal) }} KWH
                            </p>
                            <p><strong>Meter Akhir:</strong> {{ number_format($tagihan->penggunaan->meter_akhir) }} KWH
                            </p>
                            <p><strong>Jumlah Penggunaan:</strong> {{ number_format($tagihan->jumlah_meter) }} KWH</p>
                            <p><strong>Tanggal Periksa:</strong>
                                {{ \Carbon\Carbon::parse($tagihan->penggunaan->tanggal_periksa)->translatedFormat('d F Y') }}
                            </p>
                            <p><strong>Tarif Per KWH:</strong> Rp
                                {{ number_format($tagihan->penggunaan->pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}
                            </p>
                            <p><strong>Daya:</strong>
                                {{ $tagihan->penggunaan->pelanggan->tarif->daya ?? 0 }}
                            </p>
                        @else
                            <p>Data penggunaan tidak ditemukan.</p>
                        @endif
                    </div>
                </div>

                @if ($tagihan->status === 'sudah_dibayar' && $tagihan->pembayarans->count() > 0)
                    <div class="mt-6 border-t pt-4">
                        <h2 class="text-xl font-semibold mb-2">Detail Pembayaran</h2>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>ID Pembayaran</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Biaya Admin</th>
                                        <th>Total Bayar (Termasuk Admin)</th>
                                        <th>Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihan->pembayarans as $pembayaran)
                                        <tr>
                                            <td>#{{ $pembayaran->id_pembayaran }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y H:i') }}
                                            </td>
                                            <td>Rp {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</td>
                                            <td>Rp {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</td>
                                            <td>{{ $pembayaran->user->username ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif ($tagihan->status === 'belum_dibayar')
                    <div class="mt-6 text-right">
                        <p class="text-lg text-error">Tagihan ini belum dibayar.</p>
                        <button class="btn btn-success mt-4" onclick="openPaymentModal()">Bayar Sekarang</button>
                    </div>
                @endif
                <div class="mt-6 text-right">
                    <a href="{{ route('pelanggan.tagihan.index') }}" class="btn btn-secondary">Kembali ke Riwayat
                        Tagihan</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk instruksi pembayaran (contoh) --}}
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
