<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('galeri')->insert([
            [
                'id_galeri' => 1,
                'id_user' => 1,
                'id_kategori_galeri' => 1,
                'judul_galeri' => 'Workshop IT 2025',
                'deskripsi_galeri' => 'Kegiatan workshop IT yang diselenggarakan pada bulan Maret 2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_galeri' => 2,
                'id_user' => 1,
                'id_kategori_galeri' => 2,
                'judul_galeri' => 'Produk Terbaru',
                'deskripsi_galeri' => 'Rangkaian produk terbaru yang diluncurkan pada tahun 2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_galeri' => 3,
                'id_user' => 2,
                'id_kategori_galeri' => 3,
                'judul_galeri' => 'Tim Pengembangan',
                'deskripsi_galeri' => 'Tim pengembangan software perusahaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}