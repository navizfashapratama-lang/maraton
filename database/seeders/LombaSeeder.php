<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LombaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kategori ID
        $kategori5K = DB::table('kategori_event')->where('nama_kategori', '5K Run')->first()->id;
        $kategori10K = DB::table('kategori_event')->where('nama_kategori', '10K Run')->first()->id;
        $kategoriHalfMarathon = DB::table('kategori_event')->where('nama_kategori', 'Half Marathon')->first()->id;
        $kategoriMarathon = DB::table('kategori_event')->where('nama_kategori', 'Marathon')->first()->id;
        $kategoriFunRun = DB::table('kategori_event')->where('nama_kategori', 'Fun Run')->first()->id;

        // Ambil admin ID
        $adminId = DB::table('pengguna')->where('email', 'admin@gmail.com')->first()->id;

        // Data lomba sesuai dengan struktur SQL
        $events = [
            // Event 5K
            [
                'kategori_id' => $kategori5K,
                'nama' => 'Jakarta 5K Fun Run 2024',
                'kategori' => '5K',
                'deskripsi' => 'Lari santai 5K mengelilingi kawasan Monas dengan suasana yang menyenangkan untuk semua usia.',
                'tanggal' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'lokasi' => 'Monas, Jakarta Pusat',
                'harga_reguler' => 75000.00,
                'harga_premium' => 150000.00,
                'status' => 'mendatang',
                'poster_url' => null,
                'kuota_peserta' => 500,
                'pendaftaran_ditutup' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'rute_lomba' => 'Start: Monas → Jl. Medan Merdeka Utara → Bundaran HI → Jl. Medan Merdeka Selatan → Finish: Monas',
                'syarat_ketentuan' => "1. Usia minimal 10 tahun\n2. Peserta wajib membawa KTP/Kartu Pelajar\n3. Check-in dimulai 2 jam sebelum start\n4. Mengikuti semua aturan yang ditetapkan panitia",
                'fasilitas' => "Race Bib, Timing Chip, Finisher Medal, Kaos Event, Goodie Bag, Snack & Minuman, Sertifikat Digital",
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategori5K,
                'nama' => 'Bandung Night Run 5K',
                'kategori' => '5K',
                'deskripsi' => 'Lari malam dengan nuansa Bandung yang romantis, melewati ikon-ikon kota Bandung.',
                'tanggal' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'lokasi' => 'Gedung Sate, Bandung',
                'harga_reguler' => 65000.00,
                'harga_premium' => 120000.00,
                'status' => 'mendatang',
                'poster_url' => null,
                'kuota_peserta' => 300,
                'pendaftaran_ditutup' => Carbon::now()->addDays(40)->format('Y-m-d'),
                'rute_lomba' => 'Gedung Sate → Jl. Diponegoro → Alun-alun Bandung → Jl. Asia Afrika → Finish: Gedung Sate',
                'syarat_ketentuan' => "1. Usia minimal 12 tahun\n2. Wajib membawa senter/headlamp\n3. Wajib pakai reflective vest\n4. Check-in 2.5 jam sebelum start",
                'fasilitas' => "Glow Kit, Night Run Medal, Reflective Vest, Snack Malam, Sertifikat Night Run",
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Event 10K
            [
                'kategori_id' => $kategori10K,
                'nama' => 'Surabaya 10K Challenge',
                'kategori' => '10K',
                'deskripsi' => 'Challenge lari 10K dengan rute menantang melewati jembatan dan waterfront Surabaya.',
                'tanggal' => Carbon::now()->addDays(60)->format('Y-m-d'),
                'lokasi' => 'Tugu Pahlawan, Surabaya',
                'harga_reguler' => 100000.00,
                'harga_premium' => 180000.00,
                'status' => 'mendatang',
                'poster_url' => null,
                'kuota_peserta' => 400,
                'pendaftaran_ditutup' => Carbon::now()->addDays(55)->format('Y-m-d'),
                'rute_lomba' => 'Tugu Pahlawan → Jl. Tunjungan → Jembatan Suramadu → Pantai Ria Kenjeran → Finish: Tugu Pahlawan',
                'syarat_ketentuan' => "1. Usia minimal 15 tahun\n2. Pengalaman lari minimal 5K\n3. Medical check-up direkomendasikan\n4. Wajib membawa air minum",
                'fasilitas' => "Tech T-Shirt, Timing Chip, Finisher Medal, Energi Gel, Snack Box, Sertifikat Finisher",
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Event Half Marathon
            [
                'kategori_id' => $kategoriHalfMarathon,
                'nama' => 'Jogja Half Marathon',
                'kategori' => '21K',
                'deskripsi' => 'Half marathon dengan rute budaya melewati situs bersejarah Yogyakarta.',
                'tanggal' => Carbon::now()->addDays(75)->format('Y-m-d'),
                'lokasi' => 'Alun-alun Utara, Yogyakarta',
                'harga_reguler' => 150000.00,
                'harga_premium' => 250000.00,
                'status' => 'mendatang',
                'poster_url' => null,
                'kuota_peserta' => 350,
                'pendaftaran_ditutup' => Carbon::now()->addDays(70)->format('Y-m-d'),
                'rute_lomba' => 'Alun-alun Utara → Keraton Yogyakarta → Taman Sari → Malioboro → Candi Prambanan → Finish: Alun-alun Utara',
                'syarat_ketentuan' => "1. Usia minimal 17 tahun\n2. Pengalaman lari minimal 10K\n3. Medical certificate wajib\n4. Asuransi peserta",
                'fasilitas' => "Premium Jersey, Timing Chip, Finisher Medal, Energi Gel & Bar, Physiotherapy, Post-race Meal, Sertifikat Resmi",
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Event Marathon
            [
                'kategori_id' => $kategoriMarathon,
                'nama' => 'Bali International Marathon',
                'kategori' => '42K',
                'deskripsi' => 'Marathon internasional dengan pemandangan pantai dan budaya Bali yang menakjubkan.',
                'tanggal' => Carbon::now()->addDays(90)->format('Y-m-d'),
                'lokasi' => 'Sanur Beach, Bali',
                'harga_reguler' => 250000.00,
                'harga_premium' => 400000.00,
                'status' => 'mendatang',
                'poster_url' => null,
                'kuota_peserta' => 200,
                'pendaftaran_ditutup' => Carbon::now()->addDays(85)->format('Y-m-d'),
                'rute_lomba' => 'Sanur Beach → Jl. Danau Tamblingan → Pantai Kuta → GWK → Nusa Dua → Finish: Sanur Beach',
                'syarat_ketentuan' => "1. Usia 18-60 tahun\n2. Pengalaman marathon sebelumnya\n3. Medical certificate wajib\n4. Asuransi peserta internasional",
                'fasilitas' => "Marathon Kit, Timing Chip, Finisher T-Shirt, Finisher Medal, Post-race Buffet, Massage Service, Certificate International",
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Event Fun Run
            [
                'kategori_id' => $kategoriFunRun,
                'nama' => 'Color Run Jakarta 2024',
                'kategori' => '3K',
                'deskripsi' => 'Lari paling berwarna di Indonesia! Nikmati semburan warna-warni sepanjang rute.',
                'tanggal' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'lokasi' => 'GBK Senayan, Jakarta',
                'harga_reguler' => 80000.00,
                'harga_premium' => 150000.00,
                'status' => 'mendatang',
                'poster_url' => null,
                'kuota_peserta' => 1000,
                'pendaftaran_ditutup' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'rute_lomba' => 'Lapangan GBK → Istora Senayan → Tennis Indoor → Finish: GBK',
                'syarat_ketentuan' => "1. Semua usia boleh ikut\n2. Wajib pakai kaos putih\n3. Tidak boleh takut kotor\n4. Bawa kamera untuk foto",
                'fasilitas' => "Color Run Pack (kaos, kacamata, bandana), Color Powder, Finisher Medal, Party After Run, Photo Booth",
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert events dan buat paket
        foreach ($events as $event) {
            $eventId = DB::table('lomba')->insertGetId($event);

            // Buat paket Reguler untuk setiap event
            DB::table('paket')->insert([
                'lomba_id' => $eventId,
                'nama' => 'Paket Reguler',
                'termasuk_race_kit' => 1,
                'termasuk_medali' => 0,
                'termasuk_kaos' => 0,
                'termasuk_sertifikat' => 1,
                'termasuk_snack' => 1,
                'harga' => $event['harga_reguler'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat paket Premium jika ada harga premium
            if ($event['harga_premium'] > 0) {
                DB::table('paket')->insert([
                    'lomba_id' => $eventId,
                    'nama' => 'Paket Premium',
                    'termasuk_race_kit' => 1,
                    'termasuk_medali' => 1,
                    'termasuk_kaos' => 1,
                    'termasuk_sertifikat' => 1,
                    'termasuk_snack' => 1,
                    'harga' => $event['harga_premium'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Lomba dan Paket berhasil di-seed!');
    }
}