<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')->constrained('lomba')->onDelete('cascade');
            $table->string('nama');
            $table->boolean('termasuk_race_kit')->default(true);
            $table->boolean('termasuk_medali')->default(true);
            $table->boolean('termasuk_kaos')->default(false);
            $table->boolean('termasuk_sertifikat')->default(true);
            $table->boolean('termasuk_snack')->default(true);
            $table->decimal('harga', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};