<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ GANTI INI: 'pengguna' → 'users'
    protected $table = 'users';
    
    protected $fillable = [
        'nama',
        'email',
        'password',
        'peran',
        'telepon',
        'alamat',
        'foto_profil',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'terakhir_login' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ✅ Peran di tabel Anda: 'superadmin', 'admin', 'staff', 'peserta'
    public function isAdmin()
    {
        return in_array($this->peran, ['superadmin', 'admin']);
    }

    public function isStaff()
    {
        return $this->peran === 'staff';
    }

    public function isPeserta()
    {
        return $this->peran === 'peserta';
    }

    public function isSuperAdmin()
    {
        return $this->peran === 'superadmin';
    }
}