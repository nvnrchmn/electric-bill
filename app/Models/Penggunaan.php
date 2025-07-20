<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggunaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penggunaan'; // Specify the custom primary key
    protected $fillable = ['id_pelanggan', 'bulan', 'tahun', 'meter_awal', 'meter_akhir', 'tanggal_periksa', 'petugas_id'];

    // Relationship: A Penggunaan belongs to one Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relationship: One Penggunaan has many Tagihans
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_penggunaan', 'id_penggunaan');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id_user');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }
}
