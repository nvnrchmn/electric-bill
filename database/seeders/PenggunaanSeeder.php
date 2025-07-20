<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penggunaan; // Import the Penggunaan Model
use App\Models\Pelanggan; // Import Pelanggan to get id_pelanggan

class PenggunaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggan1 = Pelanggan::where('username', 'pelanggan001')->first();
        $pelanggan2 = Pelanggan::where('username', 'pelanggan002')->first();

        if ($pelanggan1) {
            Penggunaan::create([
                'id_pelanggan' => $pelanggan1->id_pelanggan,
                'bulan' => 7,
                'tahun' => 2025,
                'tanggal_periksa' => '2025-07-01', // Tanggal pemeriksaan meter
                'meter_awal' => 100,
                'meter_akhir' => 150,
            ]);
        }

        if ($pelanggan2) {
            Penggunaan::create([
                'id_pelanggan' => $pelanggan2->id_pelanggan,
                'bulan' => 7,
                'tahun' => 2025,
                'tanggal_periksa' => '2025-07-01', // Tanggal pemeriksaan meter
                'meter_awal' => 200,
                'meter_akhir' => 280,
            ]);
        }
    }
}
