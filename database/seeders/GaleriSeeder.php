<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $titles = [
            'Kantor Baru',
            'Pelatihan Tahunan',
            'Company Gathering',
            'Peluncuran Produk',
            'Seminar Teknologi',
            'Roadshow 2025',
            'Pertemuan Tim',
            'Booth Exhibition',
            'Kunjungan Industri',
            'Showcase Aplikasi',
            'Fasilitas Kantor',
            'Team Building',
            'Penghargaan Karyawan',
            'Kolaborasi Mitra',
            'Inovasi Terbaru',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $title = $faker->unique()->randomElement($titles);
            $slug = Str::slug($title);

            $galeries[] = [
                'id_galeri' => $i,
                'id_user' => $faker->numberBetween(1, 5),
                'id_kategori_galeri' => $faker->numberBetween(1, 3),
                'judul_galeri' => $title,
                'deskripsi_galeri' => $faker->paragraph(rand(1, 3)),
                'slug' => $slug,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        DB::table('galeri')->insert($galeries);
    }
}