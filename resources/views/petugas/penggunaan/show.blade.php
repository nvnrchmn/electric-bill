{{-- resources/views/petugas/penggunaan/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        {{-- Sidebar Petugas --}}
        @include('petugas.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <h1 class="text-3xl font-bold text-base-content mb-6">Detail Penggunaan Listrik</h1>

            <div class="card bg-base-300 shadow-xl p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <tbody>
                            <tr>
                                <td class="font-bold w-1/3">Pelanggan</td>
                                <td>{{ $penggunaan->pelanggan->nama_pelanggan ?? 'N/A' }}
                                    ({{ $penggunaan->pelanggan->nomor_kwh ?? 'N/A' }})</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Bulan / Tahun</td>
                                <td>{{ \Carbon\Carbon::createFromDate($penggunaan->tahun, $penggunaan->bulan)->translatedFormat('F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Tanggal Periksa</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->tanggal_periksa)->translatedFormat('d F Y H:i:s') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Meter Awal</td>
                                <td>{{ $penggunaan->meter_awal }} KWH</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Meter Akhir</td>
                                <td>{{ $penggunaan->meter_akhir }} KWH</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Jumlah Penggunaan</td>
                                <td>{{ $penggunaan->meter_akhir - $penggunaan->meter_awal }} KWH</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Dicatat Oleh Petugas</td>
                                <td>{{ $penggunaan->petugas->username ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Dibuat Pada</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->created_at)->translatedFormat('d F Y H:i:s') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Terakhir Diupdate</td>
                                <td>{{ \Carbon\Carbon::parse($penggunaan->updated_at)->translatedFormat('d F Y H:i:s') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-actions justify-end mt-6">
                    <a href="{{ route('petugas.penggunaan.edit', $penggunaan->id_penggunaan) }}"
                        class="btn btn-warning">Edit</a>
                    <a href="{{ route('petugas.penggunaan.index') }}" class="btn btn-ghost">Kembali ke Daftar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
