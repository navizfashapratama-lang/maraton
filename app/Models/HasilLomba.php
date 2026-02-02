<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilLomba extends Model
{
    use HasFactory;

    protected $table = 'hasil_lomba';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pendaftaran_id',
        'waktu_selesai',
        'waktu_total',
        'posisi',
        'kategori_umur',
        'catatan',
    ];

    /**
     * Relasi ke pendaftaran
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Format waktu ke menit:detik
     */
    public function waktuFormat()
    {
        if (!$this->waktu_total) {
            return '-';
        }
        
        $totalSeconds = strtotime($this->waktu_total) - strtotime('00:00:00');
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;
        
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Cek apakah ada hasil
     */
    public function hasResult()
    {
        return !is_null($this->waktu_total);
    }
}