<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // Data sesuai dengan tabel pengguna di SQL
        $users = [
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@maraton.id',
                'password' => Hash::make('password123'),
                'peran' => 'superadmin',
                'telepon' => '08111222333',
                'alamat' => 'Jl. Super Admin No. 1, Jakarta',
                'foto_profil' => null,
                'terakhir_login' => null,
                'is_active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Admin System',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'admin',
                'telepon' => '08122334455',
                'alamat' => 'Jl. Admin No. 2, Bandung',
                'foto_profil' => null,
                'terakhir_login' => now(),
                'is_active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Staff System',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'staff',
                'telepon' => '08133445566',
                'alamat' => 'Jl. Staff No. 3, Surabaya',
                'foto_profil' => null,
                'terakhir_login' => now()->subDays(1),
                'is_active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kasir System',
                'email' => 'kasir@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'kasir',
                'telepon' => '08144556677',
                'alamat' => 'Jl. Kasir No. 4, Yogyakarta',
                'foto_profil' => null,
                'terakhir_login' => now()->subHours(5),
                'is_active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Peserta',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'peserta',
                'telepon' => '08155667788',
                'alamat' => 'Jl. Peserta No. 5, Semarang',
                'foto_profil' => null,
                'terakhir_login' => now()->subDays(2),
                'is_active' => 0,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Rahayu',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'peserta',
                'telepon' => '08166778899',
                'alamat' => 'Jl. Peserta No. 6, Bali',
                'foto_profil' => null,
                'terakhir_login' => now()->subHours(10),
                'is_active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Andi Wijaya',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'peserta',
                'telepon' => '08177889900',
                'alamat' => 'Jl. Peserta No. 7, Medan',
                'foto_profil' => null,
                'terakhir_login' => now()->subDays(3),
                'is_active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pengguna')->insert($users);
        $this->command->info('✅ Pengguna berhasil di-seed!');
    }
}