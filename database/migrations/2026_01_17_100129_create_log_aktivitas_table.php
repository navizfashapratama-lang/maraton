<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('pengguna')->onDelete('set null');
            
            // Informasi aktivitas
            $table->string('aksi', 100);
            $table->text('deskripsi')->nullable();
            $table->string('tabel_terkait', 50)->nullable();
            $table->bigInteger('id_terkait')->nullable();
            
            // Informasi teknis
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('endpoint', 255)->nullable();
            
            $table->timestamp('created_at');
            
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};