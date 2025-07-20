<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tarif'; // Specify the custom primary key
    protected $fillable = ['daya', 'tarifperkwh'];

    // Relationship: One Tarif has many Pelanggans
    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'id_tarif', 'id_tarif');
    }
}
