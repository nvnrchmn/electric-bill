@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-200">
        @include('admin.sidebar')

        <div class="flex-1 p-8 overflow-y-auto space-y-10">
            <h1 class="text-4xl font-extrabold mb-6 border-b-4 border-primary pb-2 text-primary">Dashboard Administrator</h1>

            {{-- Statistik Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                {{-- Total Pengguna --}}
                <div
                    class="card bg-gradient-to-br from-primary to-accent text-primary-content shadow-lg hover:scale-105 transition duration-300">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 12a4 4 0 100-8 4 4 0 000 8zm0 0v2a6 6 0 006 6v0">
                                </path>
                            </svg>
                            <div>
                                <p class="text-2xl font-bold">Total Pengguna</p>
                                <p class="text-4xl font-extrabold">{{ $totalUsers }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Pelanggan --}}
                <div
                    class="card bg-gradient-to-br from-secondary to-info text-secondary-content shadow-lg hover:scale-105 transition duration-300">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A8.966 8.966 0 0112 15a8.966 8.966 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="text-2xl font-bold">Total Pelanggan</p>
                                <p class="text-4xl font-extrabold">{{ $totalPelanggan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Tagihan --}}
                <div
                    class="card bg-gradient-to-br from-info to-success text-info-content shadow-lg hover:scale-105 transition duration-300">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-2.5 0-4.5 2-4.5 4.5S9.5 17 12 17s4.5-2 4.5-4.5S14.5 8 12 8zm0-6v2m0 16v2M4 12H2m20 0h-2M4.93 4.93l1.414 1.414M18.364 18.364l1.414 1.414M4.93 19.07l1.414-1.414M18.364 5.636l1.414-1.414" />
                            </svg>
                            <div>
                                <p class="text-2xl font-bold">Total Tagihan</p>
                                <p class="text-4xl font-extrabold">{{ $totalTagihan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Pembayaran Sukses --}}
                <div
                    class="card bg-gradient-to-br from-success to-primary text-success-content shadow-lg hover:scale-105 transition duration-300">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-2xl font-bold">Pembayaran Sukses</p>
                                <p class="text-4xl font-extrabold">{{ $totalPembayaran }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Detail --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tagihan Belum Dibayar --}}
                <div
                    class="card bg-gradient-to-br from-error to-warning text-error-content shadow-lg hover:scale-105 transition duration-300">
                    <div class="card-body">
                        <h2 class="text-xl font-bold mb-2">Tagihan Belum Dibayar</h2>
                        <p class="text-5xl font-extrabold">{{ $tagihanBelumBayar }}</p>
                        <div class="card-actions justify-end mt-4">
                            <p class="text-sm opacity-90 mt-2">Tagihan yang masih tertunda</p>
                            <a href="{{ route('admin.laporan.tagihan', ['status' => 'belum_dibayar']) }}"
                                class="btn btn-sm btn-error btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                {{-- Placeholder Grafik --}}
                <div class="card bg-base-100 shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Distribusi Status Tagihan</h2>
                    <canvas id="statusPieChart" class="w-full max-h-[400px]"></canvas>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx2 = document.getElementById('statusPieChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Dibayar', 'Belum Dibayar'],
                datasets: [{
                    data: [{{ (int) $totalPembayaran }}, {{ (int) $tagihanBelumBayar }}],
                    backgroundColor: ['rgb(34,197,94)', 'rgb(239,68,68)'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endpush
