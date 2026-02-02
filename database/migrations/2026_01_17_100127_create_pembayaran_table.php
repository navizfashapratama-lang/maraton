<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran')->onDelete('cascade');
            $table->string('kode_pembayaran', 50)->unique();
            $table->string('nama_pembayar');
            $table->string('email_pembayar');
            
            // Informasi pembayaran
            $table->decimal('jumlah', 10, 2);
            $table->enum('metode_pembayaran', ['transfer', 'qris', 'cash', 'lainnya'])->default('transfer');
            $table->string('bank_tujuan', 100)->nullable();
            $table->string('nama_rekening', 255)->nullable();
            $table->string('bukti_pembayaran')->nullable();
            
            // Status & tracking
            $table->enum('status', ['menunggu', 'terverifikasi', 'ditolak', 'kadaluarsa'])->default('menunggu');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->text('catatan_admin')->nullable();
            
            // Timestamps
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('kode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};