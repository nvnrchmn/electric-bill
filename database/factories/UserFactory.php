<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Level; // Pastikan Anda mengimpor model Level

class UserFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pastikan Anda memiliki beberapa data di tabel 'levels' agar relasi berhasil
        // Jika tabel 'levels' kosong, ini akan menyebabkan error
        // $levelIds = Level::pluck('id_level')->toArray();

        // // Jika tidak ada level yang ditemukan, berikan nilai default atau tangani error
        // if (empty($levelIds)) {
        //     // Anda bisa melempar exception atau membuat level default jika tidak ada
        //     // Misalnya, jika Anda punya level dengan ID 1 sebagai default
        //     $defaultLevelId = 1; // Ganti dengan ID level default yang sesuai
        //     // Atau Anda bisa lempar exception: throw new \Exception('No levels found in the database. Please seed the levels table first.');
        // } else {
        //     $defaultLevelId = $this->faker->randomElement($levelIds);
        // }

        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $fullName = $firstName . ' ' . $lastName;

        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'nama_admin' => $fullName,
            'password' => Hash::make('password'), // Kata sandi default 'password'
            'id_level' => 3,
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'email_verified_at' => null,
            ],
        );
    }
}
