<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan untuk mengimpor model User

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel 'levels' sudah terisi data sebelum menjalankan ini
        // Misalnya, jika Anda punya LevelSeeder, jalankan dulu: $this->call(LevelSeeder::class);

        // Membuat 100.000 user
        User::factory()->count(100)->create();

        $this->command->info('100 users created successfully!');
    }
}
