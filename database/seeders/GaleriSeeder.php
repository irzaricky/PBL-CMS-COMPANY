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
            'Pelatihan Public Speaking',
            'Pelatihan Manajemen Waktu',
            'Pelatihan Manajemen Proyek',
            'Pelatihan Manajemen Risiko',
            'Pelatihan Manajemen Perubahan',
            'Pelatihan Manajemen Kualitas',
            'Pelatihan Manajemen Strategis',
            'Pelatihan Manajemen Operasional',
            'Pelatihan Manajemen Sumber Daya Manusia',
            'Pelatihan Manajemen Keuangan',
            'Pelatihan Manajemen Pemasaran',
            'Pelatihan Manajemen Penjualan',
            'Pelatihan Manajemen Produksi',
            'Pelatihan Manajemen Rantai Pasokan',
            'Pelatihan Manajemen Teknologi',
            'Pelatihan Manajemen Inovasi',
            'Pelatihan Manajemen Pengetahuan',
            'Pelatihan Manajemen Perubahan Organisasi',
            'Pelatihan Manajemen Kinerja',
            'Pelatihan Manajemen Konflik',
            'Pelatihan Manajemen Hubungan Pelanggan',
            'Pelatihan Manajemen Hubungan Masyarakat',
            'Pelatihan Manajemen Hubungan Investor',
        ];

        for ($i = 1; $i <= 60; $i++) {
            $title = $faker->unique()->randomElement($titles);
            $slug = Str::slug($title);

            $galeries[] = [
                'id_galeri' => $i,
                'id_user' => $faker->numberBetween(1, 5),
                'id_kategori_galeri' => $faker->numberBetween(1, 3),
                'judul_galeri' => $title,
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