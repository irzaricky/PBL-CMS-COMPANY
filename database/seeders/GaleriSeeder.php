<?php

namespace Database\Seeders;

use Illuminate\Http\File;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $galeries = [];

        // bagian proses image
        $sourcePath = database_path('seeders/seeder_image/');
        $targetPath = 'galeri-images';

        // Pastikan folder target ada
        Storage::disk('public')->makeDirectory($targetPath);

        // Ambil semua file di folder seeder_image
        $files = array_values(array_filter(scandir($sourcePath), fn($f) => !in_array($f, ['.', '..'])));

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
            'Pameran Teknologi',
            'Pelatihan Karyawan',
            'Kegiatan Sosial',
            'Perayaan Ulang Tahun',
            'Kegiatan Lingkungan',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->unique()->randomElement($titles);
            $slug = Str::slug($title);

            // Generate array untuk menyimpan multiple images
            $images = [];

            // Pilih dan proses beberapa gambar
            for ($j = 0; $j < 3; $j++) {
                // Pilih gambar random
                $originalFile = $files[array_rand($files)];

                // Buat nama baru biar unik
                $newFileName = Str::random(10) . '-' . $originalFile;

                // Copy ke storage
                Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $newFileName);

                // Tambahkan path gambar ke array images
                $images[] = $targetPath . '/' . $newFileName;
            }

            $galeries[] = [
                'id_galeri' => $i,
                'id_user' => $faker->numberBetween(1, 5),
                'id_kategori_galeri' => $faker->numberBetween(1, 3),
                'judul_galeri' => $title,
                'thumbnail_galeri' => json_encode($images),
                'deskripsi_galeri' => $faker->paragraph(rand(1, 3)),
                'slug' => $slug,
                'status_galeri' => $faker->randomElement(['terpublikasi', 'tidak terpublikasi']),
                'jumlah_unduhan' => $faker->numberBetween(5, 1000),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        DB::table('galeri')->insert($galeries);
    }
}