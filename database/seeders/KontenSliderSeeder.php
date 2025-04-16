<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontenSliderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('konten_slider')->insert([
            [
                'id_konten_slider' => 1,
                'id_user' => 1,
                'id_galeri' => 1,
                'id_produk' => null,
                'id_lowongan' => null,
                'id_event' => null,
                'id_artikel' => null,
                'judul_header' => 'Workshop IT 2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_konten_slider' => 2,
                'id_user' => 1,
                'id_galeri' => null,
                'id_produk' => 1,
                'id_lowongan' => null,
                'id_event' => null,
                'id_artikel' => null,
                'judul_header' => 'Aplikasi Manajemen Keuangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_konten_slider' => 3,
                'id_user' => 1,
                'id_galeri' => null,
                'id_produk' => null,
                'id_lowongan' => null,
                'id_event' => null,
                'id_artikel' => 1,
                'judul_header' => 'Penerapan AI dalam Bisnis Modern',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}