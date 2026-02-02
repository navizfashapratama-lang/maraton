<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            
            // Informasi sertifikat
            $table->enum('jenis', ['finisher', 'juara', 'partisipasi'])->default('finisher');
            $table->string('judul')->default('Sertifikat Finisher');
            $table->string('nomor_sertifikat', 50)->unique();
            
            // File management
            $table->string('file_path');
            $table->string('nama_file');
            $table->integer('ukuran_file')->default(0);
            
            // Template & customisasi
            $table->string('template', 100)->default('default');
            $table->string('background_color', 20)->default('#FFFFFF');
            $table->string('text_color', 20)->default('#000000');
            
            // Tracking
            $table->integer('diunduh')->default(0);
            $table->timestamp('terakhir_diunduh')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['pendaftaran_id']);
            $table->index(['jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};