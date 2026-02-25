<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus data lama untuk menghindari duplikasi
        DB::table('pengguna')->truncate();
        DB::table('kategori_event')->truncate();
        DB::table('lomba')->truncate();
        DB::table('paket')->truncate();
        DB::table('pendaftaran')->truncate();
        DB::table('pembayaran')->truncate();
        DB::table('log_aktivitas')->truncate();
        DB::table('notifikasi')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Seed data berurutan
        $this->call([
            KategoriEventSeeder::class,
            PenggunaSeeder::class,
            LombaSeeder::class,
        ]);
    }
}