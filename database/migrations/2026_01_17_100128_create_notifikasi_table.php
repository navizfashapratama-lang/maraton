<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->nullable()->constrained('pendaftaran')->onDelete('cascade');
            
            // Konten notifikasi
            $table->enum('jenis', ['pendaftaran_baru', 'pembayaran_baru', 'pembayaran_terverifikasi', 
                               'peserta_batal', 'reminder', 'event_terdekat', 'sistem', 'lainnya']);
            $table->string('judul');
            $table->text('pesan');
            $table->string('tautan')->nullable();
            
            // Status & tracking
            $table->boolean('dibaca')->default(false);
            $table->boolean('dikirim_email')->default(false);
            
            $table->timestamps();
            
            $table->index(['user_id', 'dibaca']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};