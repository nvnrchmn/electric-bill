<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_level'; // Specify the custom primary key
    protected $fillable = ['nama_level'];

    // --- TAMBAHKAN BARIS INI ---
    // Pastikan nilai ID ini sesuai dengan data di tabel 'levels' Anda setelah seeding
    const ADMINISTRATOR_ID = 1; // Contoh: jika 'Administrator' memiliki id_level 1
    const PETUGAS_ID = 2; // Contoh: jika 'Petugas' memiliki id_level 2
    const PELANGGAN_ID = 3; // Contoh: jika 'Pelanggan' memiliki id_level 3
    // --- AKHIR TAMBAHAN ---

    // Relationship: One Level has many Users
    public function users()
    {
        return $this->hasMany(User::class, 'id_level', 'id_level');
    }
}
