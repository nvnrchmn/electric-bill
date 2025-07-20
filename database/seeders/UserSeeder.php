<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import the User Model
use App\Models\Level; // Import the Level Model
use Illuminate\Support\Facades\Hash; // For hashing passwords

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminLevel = Level::where('nama_level', 'Administrator')->first();
        $petugasLevel = Level::where('nama_level', 'Petugas')->first();
        $pelangganLevel = Level::where('nama_level', 'Pelanggan')->first(); // If you want to create a 'pelanggan' user in the users table

        User::create([
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'nama_admin' => 'Admin Utama',
            'password' => Hash::make('password'), // Hash the password
            'id_level' => $adminLevel->id_level ?? null,
            'email_verified_at' => now(),
        ]);

        User::create([
            'username' => 'petugas_user',
            'email' => 'petugas@example.com',
            'nama_admin' => 'Petugas Lapangan',
            'password' => Hash::make('password'),
            'id_level' => $petugasLevel->id_level ?? null,
            'email_verified_at' => now(),
        ]);

        // Example for a user with 'Pelanggan' level if they also login via the Users table
        User::create([
            'username' => 'pelanggan_user',
            'email' => 'pelanggan@example.com',
            'nama_admin' => null, // Not an admin
            'password' => Hash::make('password'),
            'id_level' => $pelangganLevel->id_level ?? null,
            'email_verified_at' => now(),
        ]);
    }
}
