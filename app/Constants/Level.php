<?php

namespace App\Constants;

class Level
{
    public const ADMINISTRATOR_ID = 1;
    public const PETUGAS_ID = 2;
    public const PELANGGAN_ID = 3;

    // Optional: Nama levelnya (kalau mau pakai di view atau logika lain)
    public const LEVEL_NAMES = [
        self::ADMINISTRATOR_ID => 'Administrator',
        self::PETUGAS_ID => 'Petugas',
        self::PELANGGAN_ID => 'Pelanggan',
    ];
}
