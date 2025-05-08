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
            'Peluncuran Website',
            'Pameran Produk',
            'Kegiatan Olahraga',
            'Pelatihan Kepemimpinan',
            'Kunjungan Pelanggan',
            'Pameran Seni',
            'Pelatihan Keamanan',
            'Kegiatan CSR',
            'Pelatihan Soft Skill',
            'Pelatihan Hard Skill',
            'Pelatihan Manajemen',
            'Pelatihan Penjualan',
            'Pelatihan Pemasaran',
            'Pelatihan Teknologi',
            'Pelatihan Keuangan',
            'Pelatihan SDM',
            'Pelatihan Kesehatan',
            'Pelatihan Keselamatan',
            'Pelatihan Lingkungan',
            'Pelatihan Komunikasi',
            'Pelatihan Negosiasi',
            'Pelatihan Presentasi',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->unique()->randomElement($titles);
            $slug = Str::slug($title);

            // Pilih gambar random
            $originalFile = $files[array_rand($files)];

            // Buat nama baru biar unik
            $newFileName = Str::random(10) . '-' . $originalFile;

            // Copy ke storage
            Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $newFileName);

            $galeries[] = [
                'id_galeri' => $i,
                'id_user' => $faker->numberBetween(1, 5),
                'id_kategori_galeri' => $faker->numberBetween(1, 3),
                'judul_galeri' => $title,
                'thumbnail_galeri' => json_encode($targetPath . '/' . $newFileName),
                'deskripsi_galeri' => $faker->paragraph(rand(1, 3)),
                'slug' => $slug,
                'jumlah_unduhan' => $faker->numberBetween(5, int2: 1000),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        DB::table('galeri')->insert($galeries);
    }
}