<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'aksi',
        'deskripsi',
        'tabel_terkait',
        'id_terkait',
        'ip_address',
        'user_agent',
        'method',
        'endpoint',
    ];

    public $timestamps = false;

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Buat log aktivitas baru
     */
    public static function createLog($userId, $aksi, $deskripsi = null, $tabelTerkait = null, $idTerkait = null)
    {
        $request = request();
        
        return self::create([
            'user_id' => $userId,
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'tabel_terkait' => $tabelTerkait,
            'id_terkait' => $idTerkait,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'created_at' => now(),
        ]);
    }
}