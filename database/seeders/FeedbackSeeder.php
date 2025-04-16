<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('feedback')->insert([
            [
                'id_feedback' => 1,
                'id_user' => 1,
                'subjek_feedback' => 'Saran Perbaikan Website',
                'isi_feedback' => 'Website perlu ditambahkan fitur pencarian untuk memudahkan pengguna menemukan informasi',
                'tanggal_feedback' => now(),
                'tanggapan_feedback' => 'Terima kasih atas sarannya, kami sedang mengembangkan fitur tersebut',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_feedback' => 2,
                'id_user' => 2,
                'subjek_feedback' => 'Peningkatan Kualitas Layanan',
                'isi_feedback' => 'Layanan customer service perlu ditingkatkan respons timenya',
                'tanggal_feedback' => now(),
                'tanggapan_feedback' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_feedback' => 3,
                'id_user' => 2,
                'subjek_feedback' => 'Laporan Bug Aplikasi',
                'isi_feedback' => 'Ditemukan bug pada aplikasi saat mengunduh laporan dalam format PDF',
                'tanggal_feedback' => now(),
                'tanggapan_feedback' => 'Bug sudah diperbaiki pada versi 2.3.1, silahkan update aplikasi Anda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}