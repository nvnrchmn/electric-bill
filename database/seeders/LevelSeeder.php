<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level; // Import the Level Model

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create(['nama_level' => 'Administrator']);
        Level::create(['nama_level' => 'Petugas']);
        Level::create(['nama_level' => 'Pelanggan']);
    }
}
