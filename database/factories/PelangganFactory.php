<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Tarif;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName,
            'nomor_kwh' => $this->faker->unique()->numerify('KWH-#######'),
            'nama_pelanggan' => $this->faker->name,
            'alamat' => $this->faker->address,
            'id_tarif' => Tarif::inRandomOrder()->first()->id_tarif ?? 2, // Ambil random tarif, default 1 jika tidak ada
            'id_user' => rand(3, 124),
        ];
    }
}
