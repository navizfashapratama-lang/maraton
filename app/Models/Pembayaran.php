<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    
    protected $fillable = [
        'id_pendaftaran',
        'kode_pembayaran',
        'nama_pembayar',
        'email_pembayar',
        'jumlah',
        'metode_pembayaran',  // PERBAIKI: di database nama kolomnya metode_pembayaran, bukan metode
        'bank_tujuan',
        'nama_rekening',
        'bukti_pembayaran',   // PERBAIKI: di database nama kolomnya bukti_pembayaran, bukan bukti
        'status',
        'diverifikasi_oleh',
        'catatan_admin',
        'tanggal_bayar',
        'tanggal_verifikasi'
    ];
    
    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
        'tanggal_verifikasi' => 'datetime'
    ];
    
    // Relasi dengan pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran');
    }
    
    // Relasi dengan pengguna (verifikator)
    public function verifikator()
    {
        return $this->belongsTo(Pengguna::class, 'diverifikasi_oleh');
    }
}