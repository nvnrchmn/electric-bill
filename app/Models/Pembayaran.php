<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pembayaran'; // Specify the custom primary key
    protected $table = 'pembayarans';
    protected $fillable = [
        'id_tagihan',
        'id_pelanggan',
        'id_user', // Refers to the User model
        'tanggal_pembayaran',
        'bulan_bayar',
        'biaya_admin',
        'total_bayar',
        'keterangan',
        'metode_pembayaran',
    ];

    // Relationship: A Pembayaran belongs to one Tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id_tagihan');
    }

    // Relationship: A Pembayaran belongs to one Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function petugasPelanggan()
    {
        return $this->hasOneThrough(
            Pelanggan::class,
            Tagihan::class,
            'id_tagihan', // Foreign key on Tagihan table
            'id_pelanggan', // Foreign key on Pelanggan table
            'id_tagihan', // Local key on Pembayaran table
            'id_pelanggan', // Local key on Tagihan table
        );
    }

    // Relationship: A Pembayaran belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
