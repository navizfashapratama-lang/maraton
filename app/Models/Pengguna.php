<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    
    protected $fillable = [
        'nama',
        'email',
        'password',
        'peran',
        'telepon',
        'alamat',
        'foto_profil',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'terakhir_login' => 'datetime'
    ];

    // Mutator untuk password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Scope untuk filter peran
    public function scopeRole($query, $role)
    {
        if ($role) {
            return $query->where('peran', $role);
        }
        return $query;
    }

    // Scope untuk aktif saja
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('telepon', 'like', "%{$search}%");
        }
        return $query;
    }

    // Cek apakah admin
    public function isAdmin()
    {
        return $this->peran === 'admin' || $this->peran === 'superadmin';
    }

    // Cek apakah staff
    public function isStaff()
    {
        return $this->peran === 'staff';
    }

    // Cek apakah peserta
    public function isPeserta()
    {
        return $this->peran === 'peserta';
    }
}