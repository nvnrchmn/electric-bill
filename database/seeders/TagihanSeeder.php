<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tagihan; // Import Tagihan Model
use App\Models\Penggunaan; // Import Penggunaan to get id_penggunaan
use App\Models\Pelanggan; // Import Pelanggan to get id_pelanggan
use App\Models\Tarif; // To calculate total amount

class TagihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penggunaan1 = Penggunaan::where('meter_awal', 100)->first(); // Get specific usage
        $penggunaan2 = Penggunaan::where('meter_awal', 200)->first();

        if ($penggunaan1 && $penggunaan1->pelanggan && $penggunaan1->pelanggan->tarif) {
            $jumlahMeter = $penggunaan1->meter_akhir - $penggunaan1->meter_awal;
            $tarifPerKwh = $penggunaan1->pelanggan->tarif->tarifperkwh;

            Tagihan::create([
                'id_penggunaan' => $penggunaan1->id_penggunaan,
                'id_pelanggan' => $penggunaan1->id_pelanggan,
                'bulan' => $penggunaan1->bulan,
                'tahun' => $penggunaan1->tahun,
                'jumlah_meter' => $jumlahMeter,
                'status' => 'Belum Bayar',
            ]);
        }

        if ($penggunaan2 && $penggunaan2->pelanggan && $penggunaan2->pelanggan->tarif) {
            $jumlahMeter = $penggunaan2->meter_akhir - $penggunaan2->meter_awal;
            $tarifPerKwh = $penggunaan2->pelanggan->tarif->tarifperkwh;

            Tagihan::create([
                'id_penggunaan' => $penggunaan2->id_penggunaan,
                'id_pelanggan' => $penggunaan2->id_pelanggan,
                'bulan' => $penggunaan2->bulan,
                'tahun' => $penggunaan2->tahun,
                'jumlah_meter' => $jumlahMeter,
                'status' => 'Belum Bayar',
            ]);
        }
    }
}
