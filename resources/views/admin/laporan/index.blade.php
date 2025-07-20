{{-- resources/views/admin/laporan/index.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan layout Anda --}}

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar') {{-- Sesuaikan sidebar Anda --}}

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-8">Pilih Jenis Laporan</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card shadow-xl image-full">
                    <figure><img
                            src="https://media.istockphoto.com/id/676695006/id/vektor/konsep-konsumsi-energi-dan-daya-yang-mahal-koin-di-depan-meteran-listrik-ilustrasi-yang.jpg?s=612x612&w=0&k=20&c=1VukssQFFtXHyoRjaVLose1GkqJIUrmkkJ9qFtCTThY="
                            alt="Laporan Penggunaan" />
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title">Laporan Penggunaan Listrik</h2>
                        <p>Menampilkan data penggunaan listrik pelanggan.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('admin.laporan.penggunaan') }}" class="btn btn-primary">Lihat Laporan</a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-xl image-full">
                    <figure><img
                            src="https://media.istockphoto.com/id/1365317732/id/foto/tagihan-listrik-mengisi-kertas.jpg?s=612x612&w=0&k=20&c=88a2a5a9Pa_R5KwYjY2yZBMOy8HNSHP49MNal-mtMhs="
                            alt="Laporan Tagihan" />
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title">Laporan Tagihan</h2>
                        <p>Menampilkan status dan detail tagihan pelanggan.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('admin.laporan.tagihan') }}" class="btn btn-secondary">Lihat Laporan</a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-xl image-full">
                    <figure><img
                            src="https://media.istockphoto.com/id/175184020/id/foto/meter-listrik-dan-uang-kertas-euro.jpg?s=612x612&w=0&k=20&c=If6R_Skd-5_0xWx4z_PuhEr6_EapipBovdEz4_djGZY="
                            alt="Laporan Pembayaran" /></figure>
                    <div class="card-body">
                        <h2 class="card-title">Laporan Pembayaran</h2>
                        <p>Menampilkan data pembayaran yang telah dicatat.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('admin.laporan.pembayaran') }}" class="btn btn-accent">Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
