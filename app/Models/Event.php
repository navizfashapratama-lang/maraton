<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    // Tentukan nama tabel karena nama model berbeda dengan tabel
    protected $table = 'lomba';

    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
        'tanggal',
        'lokasi',
        'harga_reguler',
        'harga_premium',
        'status',
        'poster_url',
        'kuota_peserta',
        'pendaftaran_ditutup',
        'rute_lomba',
        'syarat_ketentuan',
        'fasilitas',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pendaftaran_ditutup' => 'date',
        'harga_reguler' => 'float',
        'harga_premium' => 'float',
        'kuota_peserta' => 'integer'
    ];

    // Accessor untuk has_packages
    public function getHasPackagesAttribute()
    {
        return ($this->harga_reguler > 0 || $this->harga_premium > 0);
    }

    // Accessor untuk harga_min
    public function getHargaMinAttribute()
    {
        if ($this->harga_reguler > 0 && $this->harga_premium > 0) {
            return min($this->harga_reguler, $this->harga_premium);
        } elseif ($this->harga_reguler > 0) {
            return $this->harga_reguler;
        } elseif ($this->harga_premium > 0) {
            return $this->harga_premium;
        }
        return 0;
    }

    // Accessor untuk harga_standar (alias untuk harga_reguler)
    public function getHargaStandarAttribute()
    {
        return $this->harga_reguler;
    }

    // Scope untuk filter
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['kategori']) && $filters['kategori']) {
            $query->where('kategori', $filters['kategori']);
        }
        
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['search']) && $filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('nama', 'LIKE', '%' . $filters['search'] . '%')
                  ->orWhere('lokasi', 'LIKE', '%' . $filters['search'] . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $filters['search'] . '%');
            });
        }
        
        return $query;
    }

    // Scope untuk event mendatang
    public function scopeMendatang($query)
    {
        return $query->where('status', 'mendatang')
                    ->where('tanggal', '>=', now()->toDateString())
                    ->orderBy('tanggal', 'asc');
    }

    // Scope untuk event selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai')
                    ->orWhere(function($q) {
                        $q->where('status', 'mendatang')
                          ->where('tanggal', '<', now()->toDateString());
                    })
                    ->orderBy('tanggal', 'desc');
    }

    // Relasi dengan paket
    public function paket()
    {
        return $this->hasMany(Paket::class, 'lomba_id');
    }

    // Relasi dengan pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_lomba');
    }
}