<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->foreignId('id_lomba')->constrained('lomba')->onDelete('cascade');
            $table->foreignId('id_paket')->constrained('paket')->onDelete('cascade');
            
            // Data pribadi peserta
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('telepon', 20);
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('ukuran_jersey', ['XS', 'S', 'M', 'L', 'XL', 'XXL'])->nullable();
            
            // Informasi pendaftaran
            $table->string('kode_pendaftaran', 20)->unique();
            $table->string('nomor_start', 20)->nullable();
            $table->text('catatan_khusus')->nullable();
            
            // Status
            $table->enum('status_pendaftaran', ['menunggu', 'disetujui', 'ditolak', 'dibatalkan'])->default('menunggu');
            $table->enum('status_pembayaran', ['menunggu', 'lunas', 'gagal'])->default('menunggu');
            
            // Timestamps tambahan
            $table->timestamp('dibatalkan_pada')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamps();
            
            $table->index(['status_pendaftaran', 'status_pembayaran']);
            $table->index('kode_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};