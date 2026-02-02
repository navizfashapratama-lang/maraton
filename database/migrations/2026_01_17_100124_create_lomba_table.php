<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_event')->onDelete('set null');
            $table->string('nama');
            $table->string('kategori', 50);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->string('lokasi')->nullable();
            $table->decimal('harga_reguler', 10, 2)->nullable();
            $table->decimal('harga_premium', 10, 2)->nullable();
            $table->enum('status', ['mendatang', 'selesai', 'dibatalkan'])->default('mendatang');
            $table->string('poster_url')->nullable();
            $table->integer('kuota_peserta')->default(100);
            $table->date('pendaftaran_ditutup')->nullable();
            $table->text('rute_lomba')->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lomba');
    }
};