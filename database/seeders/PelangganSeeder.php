<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelanggan; // Import the Pelanggan Model
use App\Models\Tarif; // Import Tarif to get id_tarif
use App\Models\User; // Import User if needed
use Illuminate\Support\Facades\Hash; // For hashing passwords

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarif900 = Tarif::where('daya', '900 VA')->first();
        $tarif1300 = Tarif::where('daya', '1300 VA')->first();

        Pelanggan::create([
            'username' => 'pelanggan001',
            'nomor_kwh' => 'P001KWH123',
            'nama_pelanggan' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta',
            'id_tarif' => $tarif900->id_tarif ?? null,
            // 'id_user' => 3, // Assuming no user is assigned yet
        ]);

        Pelanggan::create([
            'username' => 'pelanggan002',
            'nomor_kwh' => 'P002KWH456',
            'nama_pelanggan' => 'Siti Aminah',
            'alamat' => 'Jl. Pahlawan No. 25, Surabaya',
            'id_tarif' => $tarif1300->id_tarif ?? null,
            // 'id_user' => 3, // Assuming no user is assigned yet
        ]);
    }
}
