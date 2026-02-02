<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pendaftaran_id',
        'jenis',
        'judul',
        'nomor_sertifikat',
        'file_path',
        'nama_file',
        'ukuran_file',
        'template',
        'background_color',
        'text_color',
        'diunduh',
        'terakhir_diunduh',
        'is_active',
    ];

    protected $casts = [
        'diunduh' => 'integer',
        'terakhir_diunduh' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke pendaftaran
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Scope untuk sertifikat aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk sertifikat finisher
     */
    public function scopeFinisher($query)
    {
        return $query->where('jenis', 'finisher');
    }

    /**
     * Tambah counter download
     */
    public function incrementDownload()
    {
        $this->diunduh += 1;
        $this->terakhir_diunduh = now();
        $this->save();
    }

    /**
     * Generate nomor sertifikat otomatis
     */
    public static function generateNomor()
    {
        return 'CERT-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}