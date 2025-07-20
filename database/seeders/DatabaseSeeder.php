<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            TarifSeeder::class,
            UserSeeder::class, // UserSeeder depends on LevelSeeder
            PelangganSeeder::class, // PelangganSeeder depends on TarifSeeder
            // PenggunaanSeeder::class, // PenggunaanSeeder depends on PelangganSeeder
            // TagihanSeeder::class, // TagihanSeeder depends on PenggunaanSeeder and PelangganSeeder
            // PembayaranSeeder::class, // PembayaranSeeder depends on TagihanSeeder, PelangganSeeder, and UserSeeder
        ]);
    }
}
