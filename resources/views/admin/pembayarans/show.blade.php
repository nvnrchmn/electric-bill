{{-- resources/views/admin/pembayarans/show.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Detail Pembayaran #{{ $pembayaran->id_pembayaran }}</h1>

            <div class="shadow-lg rounded-lg p-6 min-w-full">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                        <p class="mb-2"><strong>Tanggal Pembayaran:</strong>
                            {{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('l, d F Y H:i:s') }}
                        </p>
                        <p class="mb-2"><strong>Bulan Pembayaran:</strong> {{ $pembayaran->bulan_bayar }}</p>
                        {{-- Jika bulan_bayar di ERD adalah bulan saat pembayaran dicatat --}}
                        <p class="mb-2"><strong>Biaya Admin:</strong> Rp
                            {{ number_format($pembayaran->biaya_admin, 2, ',', '.') }}</p>
                        <p class="mb-2"><strong>Total Dibayar:</strong> Rp
                            {{ number_format($pembayaran->total_bayar, 2, ',', '.') }}</p>
                        <p class="mb-2"><strong>Dicatat Oleh:</strong> {{ $pembayaran->user->nama_admin ?? 'N/A' }}
                            (Level: {{ $pembayaran->user->level->nama_level ?? 'N/A' }})</p>
                        <p class="mb-2"><strong>Dibuat Pada:</strong>
                            {{ $pembayaran->created_at->translatedFormat('l, d F Y H:i:s') }}</p>
                        <p class="mb-2"><strong>Diperbarui Pada:</strong>
                            {{ $pembayaran->updated_at->translatedFormat('l, d F Y H:i:s') }}</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-4 text-base-content">Informasi Tagihan</h2>
                        <p class="mb-2"><strong>ID Tagihan:</strong> {{ $pembayaran->tagihan->id_tagihan ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Bulan/Tahun Tagihan:</strong>
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
                                $bulanTagihan = $pembayaran->tagihan
                                    ? $months[str_pad($pembayaran->tagihan->bulan, 2, '0', STR_PAD_LEFT)] ??
                                        $pembayaran->tagihan->bulan
                                    : 'N/A';
                                $tahunTagihan = $pembayaran->tagihan ? $pembayaran->tagihan->tahun : 'N/A';
                            @endphp
                            {{ $bulanTagihan }}/{{ $tahunTagihan }}
                        </p>
                        <p class="mb-2"><strong>Jumlah Meter:</strong>
                            {{ number_format($pembayaran->tagihan->jumlah_meter ?? 0) }} KWH</p>
                        <p class="mb-2"><strong>Total Tagihan Awal:</strong> Rp
                            {{ number_format($pembayaran->tagihan->total_tagihan ?? 0, 2, ',', '.') }}</p>
                        <p class="mb-2"><strong>Status Tagihan:</strong>
                            @if ($pembayaran->tagihan->status == 'belum_dibayar')
                                <span
                                    class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $pembayaran->tagihan->status)) }}</span>
                            @elseif($pembayaran->tagihan->status == 'sudah_dibayar')
                                <span
                                    class="badge badge-success">{{ ucfirst(str_replace('_', ' ', $pembayaran->tagihan->status)) }}</span>
                            @else
                                <span
                                    class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $pembayaran->tagihan->status)) }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-span-2">
                        <h2 class="text-xl font-semibold mb-4 text-base-content">Informasi Pelanggan</h2>
                        <p class="mb-2"><strong>Nama Pelanggan:</strong>
                            {{ $pembayaran->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Nomor KWH:</strong> {{ $pembayaran->pelanggan->nomor_kwh ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Alamat:</strong> {{ $pembayaran->pelanggan->alamat ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Daya/Tarif:</strong> {{ $pembayaran->pelanggan->tarif->daya ?? 'N/A' }}
                            (Rp {{ number_format($pembayaran->pelanggan->tarif->tarifperkwh ?? 0, 2, ',', '.') }}/KWH)
                        </p>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('admin.pembayarans.index') }}" class="btn btn-primary">Kembali ke Daftar
                        Pembayaran</a>
                </div>
            </div>
        </div>
    </div>
@endsection
