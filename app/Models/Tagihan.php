<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tagihan'; // Specify the custom primary key
    protected $fillable = ['id_penggunaan', 'id_pelanggan', 'bulan', 'tahun', 'jumlah_meter', 'total_tagihan', 'status'];

    // Relationship: A Tagihan belongs to one Penggunaan
    public function penggunaan()
    {
        return $this->belongsTo(Penggunaan::class, 'id_penggunaan', 'id_penggunaan');
    }

    // Relationship: A Tagihan belongs to one Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relationship: One Tagihan has many Pembayarans
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_tagihan', 'id_tagihan');
    }
}
