<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;
    
    protected $table = 'pendaftaran';
    
    protected $fillable = [
        'id_pengguna',
        'id_lomba',
        'id_paket',
        'status',
        'nama_lengkap',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'ukuran_jersey',
        'kode_pendaftaran',
        'nomor_start',
        'catatan_khusus',
        'status_pendaftaran',
        'status_pembayaran',
        'dibatalkan_pada',
        'alasan_pembatalan'
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'dibatalkan_pada' => 'datetime'
    ];
    
    // Relasi dengan lomba
    public function lomba()
    {
        return $this->belongsTo(Lomba::class, 'id_lomba');
    }
    
    // Relasi dengan pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
    
    // Relasi dengan pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pendaftaran');
    }
}