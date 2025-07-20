<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tarif; // Import the Tarif Model

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tarif::create([
            'daya' => '900 VA',
            'tarifperkwh' => 1352.0,
        ]);
        Tarif::create([
            'daya' => '1300 VA',
            'tarifperkwh' => 1444.7,
        ]);
        Tarif::create([
            'daya' => '2200 VA',
            'tarifperkwh' => 1444.7,
        ]);
    }
}
