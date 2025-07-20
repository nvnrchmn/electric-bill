<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pembayaran; // Import Pembayaran Model
use App\Models\Tagihan; // Import Tagihan to get id_tagihan
use App\Models\User; // Import User to get id_user
use App\Models\Pelanggan; // Import Pelanggan to get id_pelanggan
use App\Models\Tarif; // To calculate actual amount

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagihan1 = Tagihan::where('bulan', 'Juli')
            ->where('tahun', '2025')
            ->whereHas('pelanggan', function ($query) {
                $query->where('username', 'pelanggan001');
            })
            ->first();

        $adminUser = User::where('email', 'admin@example.com')->first();
        $pelangganUser = User::where('email', 'pelanggan@example.com')->first(); // User entry for a customer if they also login

        if ($tagihan1 && $adminUser) {
            $biayaAdmin = 2500.0;
            $jumlahMeter = $tagihan1->jumlah_meter;
            $tarifPerKwh = $tagihan1->pelanggan->tarif->tarifperkwh;
            $totalTagihanPokok = $jumlahMeter * $tarifPerKwh;
            $totalBayar = $totalTagihanPokok + $biayaAdmin;

            Pembayaran::create([
                'id_tagihan' => $tagihan1->id_tagihan,
                'id_pelanggan' => $tagihan1->id_pelanggan,
                'id_user' => $adminUser->id_user, // User yang melakukan pembayaran (bisa admin atau petugas)
                'tanggal_pembayaran' => now(),
                'bulan_bayar' => 'Juli',
                'biaya_admin' => $biayaAdmin,
                'total_bayar' => $totalBayar,
            ]);

            // Update tagihan status
            $tagihan1->status = 'Sudah Bayar';
            $tagihan1->save();
        }

        // Example for the second tagihan (if you want to seed it as unpaid or paid by another user)
        // You can add more complex seeding logic here.
    }
}
