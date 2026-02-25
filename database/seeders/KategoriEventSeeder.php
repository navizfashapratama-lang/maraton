<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriEventSeeder extends Seeder
{
    public function run(): void
    {
        // Data sesuai dengan field di SQL: id, nama_kategori, deskripsi, ikon, warna, created_at, updated_at
        $kategoris = [
            [
                'nama_kategori' => '5K Run',
                'deskripsi' => 'Lari jarak 5 kilometer untuk pemula dan semua usia',
                'ikon' => 'fa-running',
                'warna' => '#4ECDC4',
            ],
            [
                'nama_kategori' => '10K Run',
                'deskripsi' => 'Lari jarak 10 kilometer untuk menengah',
                'ikon' => 'fa-road',
                'warna' => '#FF6B6B',
            ],
            [
                'nama_kategori' => 'Half Marathon',
                'deskripsi' => 'Lari jarak 21 kilometer untuk pelari berpengalaman',
                'ikon' => 'fa-flag-checkered',
                'warna' => '#1A535C',
            ],
            [
                'nama_kategori' => 'Marathon',
                'deskripsi' => 'Lari jarak penuh 42 kilometer untuk profesional',
                'ikon' => 'fa-trophy',
                'warna' => '#55DDE0',
            ],
            [
                'nama_kategori' => 'Fun Run',
                'deskripsi' => 'Lari santai dengan berbagai tema menyenangkan',
                'ikon' => 'fa-smile',
                'warna' => '#F26419',
            ],
            [
                'nama_kategori' => 'Trail Run',
                'deskripsi' => 'Lari di jalur alam dan pegunungan',
                'ikon' => 'fa-mountain',
                'warna' => '#2F4858',
            ],
            [
                'nama_kategori' => 'Charity Run',
                'deskripsi' => 'Lari untuk tujuan sosial dan amal',
                'ikon' => 'fa-hand-holding-heart',
                'warna' => '#F6AE2D',
            ],
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategori_event')->insert([
                'nama_kategori' => $kategori['nama_kategori'],
                'deskripsi' => $kategori['deskripsi'],
                'ikon' => $kategori['ikon'],
                'warna' => $kategori['warna'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Kategori Event berhasil di-seed!');
    }
}