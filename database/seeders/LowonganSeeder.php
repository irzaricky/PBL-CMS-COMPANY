<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lowongan')->insert([
            [
                'id_lowongan' => 1,
                'id_user' => 1,
                'judul_lowongan' => 'Full Stack Developer',
                'deskripsi_pekerjaan' => 'Kami mencari Full Stack Developer dengan pengalaman minimal 2 tahun dalam pengembangan web menggunakan Laravel dan Vue.js.',
                'jenis_lowongan' => 'Full-time',
                'gaji' => 12000000,
                'tanggal_dibuka' => now(),
                'tanggal_ditutup' => now()->addDays(30),
                'tenaga_dibutuhkan' => 2,
            ],
            [
                'id_lowongan' => 2,
                'id_user' => 1,
                'judul_lowongan' => 'UI/UX Designer',
                'deskripsi_pekerjaan' => 'Mencari UI/UX Designer dengan keahlian dalam Figma, Adobe XD, dan pemahaman tentang user experience.',
                'jenis_lowongan' => 'Full-time',
                'gaji' => 10000000,
                'tanggal_dibuka' => now(),
                'tanggal_ditutup' => now()->addDays(14),
                'tenaga_dibutuhkan' => 1,
            ],
            [
                'id_lowongan' => 3,
                'id_user' => 2,
                'judul_lowongan' => 'Marketing Intern',
                'deskripsi_pekerjaan' => 'Program magang untuk mahasiswa semester akhir jurusan Marketing atau Komunikasi.',
                'jenis_lowongan' => 'Internship',
                'gaji' => 2500000,
                'tanggal_dibuka' => now(),
                'tanggal_ditutup' => now()->addDays(7),
                'tenaga_dibutuhkan' => 3,
            ],
        ]);
    }
}