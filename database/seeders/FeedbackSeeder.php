<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $subjects = [
            'Laporan Bug Aplikasi',
            'Peningkatan Kualitas Layanan',
            'Saran Perbaikan Website',
            'Saran Fitur Baru',
            'Perbaikan Antarmuka',
            'Masukan untuk Produk',
            'Laporan Error',
            'Pertanyaan tentang Layanan',
            'Kendala saat Registrasi',
            'Masalah Login',
            'Kritik untuk Sistem',
            'Kesan Penggunaan Aplikasi',
            'Permintaan Bantuan Teknis'
        ];

        for ($i = 1; $i <= 20; $i++) {
            $hasResponse = $faker->boolean(70);

            $feedbacks[] = [
                'id_feedback' => $i,
                'id_user' => $faker->numberBetween(9, 13),
                'subjek_feedback' => $faker->randomElement($subjects),
                'isi_feedback' => $faker->paragraph(rand(1, 3)),
                'tanggal_feedback' => $faker->dateTimeBetween('-3 months', 'now'),
                'tanggapan_feedback' => $hasResponse ? $faker->paragraph(rand(1, 2)) : null,
            ];
        }

        DB::table('feedback')->insert($feedbacks);
    }
}