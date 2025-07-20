<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pelanggan'; // Specify the custom primary key
    protected $fillable = ['username', 'nomor_kwh', 'nama_pelanggan', 'alamat', 'id_tarif', 'id_user'];

    // Hide the password attribute when converting to array/JSON
    protected $hidden = ['password'];

    // Relationship: A Pelanggan belongs to one Tarif
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relationship: A Pelanggan has many Penggunaans
    public function penggunaans()
    {
        return $this->hasMany(Penggunaan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relationship: A Pelanggan has many Tagihans
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relationship: A Pelanggan has many Pembayarans
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_pelanggan', 'id_pelanggan');
    }
}
