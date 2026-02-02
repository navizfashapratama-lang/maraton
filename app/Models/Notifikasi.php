<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'pendaftaran_id',
        'jenis',
        'judul',
        'pesan',
        'tautan',
        'dibaca',
        'dikirim_email',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'dikirim_email' => 'boolean',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pendaftaran
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Scope untuk notifikasi belum dibaca
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }

    /**
     * Tandai sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->dibaca = true;
        $this->save();
    }

    /**
     * Tandai sebagai sudah dikirim email
     */
    public function markAsEmailSent()
    {
        $this->dikirim_email = true;
        $this->save();
    }

    /**
     * Kirim notifikasi baru
     */
    public static function send($userId, $jenis, $judul, $pesan, $tautan = null, $pendaftaranId = null)
    {
        return self::create([
            'user_id' => $userId,
            'pendaftaran_id' => $pendaftaranId,
            'jenis' => $jenis,
            'judul' => $judul,
            'pesan' => $pesan,
            'tautan' => $tautan,
            'dibaca' => false,
            'dikirim_email' => false,
        ]);
    }
}