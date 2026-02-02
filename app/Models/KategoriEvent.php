<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriEvent extends Model
{
    use HasFactory;

    protected $table = 'kategori_event';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'ikon',
        'warna',
    ];

    /**
     * Relasi ke lomba
     */
    public function lomba()
    {
        return $this->hasMany(Lomba::class, 'kategori_id');
    }
}