<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lomba extends Model
{
    use HasFactory;

    protected $table = 'lomba';
    
    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
        'tanggal',
        'lokasi',
        'harga_standar',
        'harga_premium',
        'jenis_event',      // Hanya: berbayar, gratis (tanpa donasi)
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
        'harga_standar' => 'decimal:2',
        'harga_premium' => 'decimal:2',
        'fasilitas' => 'array'
    ];
    
    /**
     * Relasi dengan pendaftaran
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_lomba');
    }
    
    /**
     * Relasi dengan paket
     */
    public function paket()
    {
        return $this->hasMany(Paket::class, 'lomba_id');
    }
    
    /**
     * Accessor untuk harga_reguler (alias ke harga_standar)
     */
    public function getHargaRegulerAttribute()
    {
        return $this->harga_standar;
    }
    
    /**
     * Mutator untuk harga_reguler (alias ke harga_standar)
     */
    public function setHargaRegulerAttribute($value)
    {
        $this->attributes['harga_standar'] = $value;
    }
    
    /**
     * Scope untuk event aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'mendatang')
                    ->where('tanggal', '>=', now());
    }
    
    /**
     * Scope untuk event berbayar
     */
    public function scopeBerbayar($query)
    {
        return $query->where('jenis_event', 'berbayar');
    }
    
    /**
     * Scope untuk event gratis
     */
    public function scopeGratis($query)
    {
        return $query->where('jenis_event', 'gratis');
    }
    
    /**
     * Check apakah event gratis
     */
    public function isGratis()
    {
        return $this->jenis_event === 'gratis';
    }
    
    /**
     * Check apakah event berbayar
     */
    public function isBerbayar()
    {
        return $this->jenis_event === 'berbayar';
    }
    
    /**
     * Get label jenis event
     */
    public function getJenisEventLabelAttribute()
    {
        $labels = [
            'berbayar' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Berbayar</span>',
            'gratis' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Gratis</span>',
        ];
        
        return $labels[$this->jenis_event] ?? '<span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }
    
    /**
     * Get label status event
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'mendatang' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Mendatang</span>',
            'selesai' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Selesai</span>',
            'dibatalkan' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">Dibatalkan</span>',
        ];
        
        return $labels[$this->status] ?? '<span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }
    
    /**
     * Get formatted harga
     */
    public function getHargaFormattedAttribute()
    {
        if ($this->isGratis()) {
            return '<span class="font-bold text-green-600">GRATIS</span>';
        }
        
        $harga = $this->harga_standar;
        if ($this->harga_premium > $this->harga_standar) {
            $harga = $this->harga_premium;
        }
        
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }
    
    /**
     * Get paket tersedia
     */
    public function getPaketTersediaAttribute()
    {
        if ($this->isGratis()) {
            return ['Gratis'];
        }
        
        $paket = ['Reguler'];
        if ($this->harga_premium > 0) {
            $paket[] = 'Premium';
        }
        
        return $paket;
    }
}