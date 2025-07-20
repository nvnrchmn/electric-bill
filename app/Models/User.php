<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'id_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', // Added based on your ERD
        'email',
        'nama_admin', // Added based on your ERD
        'password',
        'id_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship: A User belongs to one Level
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    // Relationship: A User has many Pembayarans (if the 'id_user' in pembayaran refers to this 'User' model)
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_user', 'id_user');
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'id_user', 'id_user');
    }
}
