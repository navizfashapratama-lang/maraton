<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';
    
    protected $fillable = [
        'lomba_id',
        'nama',
        'termasuk_race_kit',
        'termasuk_medali',
        'termasuk_kaos',
        'harga',
    ];
    
    protected $casts = [
        'termasuk_race_kit' => 'boolean',
        'termasuk_medali' => 'boolean',
        'termasuk_kaos' => 'boolean',
        'harga' => 'decimal:2'
    ];
    
    /**
     * Relasi dengan lomba
     */
    public function lomba()
    {
        return $this->belongsTo(Lomba::class, 'lomba_id');
    }
    
    /**
     * Relasi dengan pendaftaran
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_paket');
    }
    
    /**
     * Accessor untuk daftar fasilitas
     */
    public function getFasilitasListAttribute()
    {
        $fasilitas = [];
        
        if ($this->termasuk_race_kit) {
            $fasilitas[] = 'Race Kit';
        }
        if ($this->termasuk_medali) {
            $fasilitas[] = 'Medali Finisher';
        }
        if ($this->termasuk_kaos) {
            $fasilitas[] = 'Kaos Event';
        }
        
        return $fasilitas;
    }
}