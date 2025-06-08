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
                'id_konten_slider' => null,
                'id_user' => null,
                'id_galeri' => null,
                'id_produk' => null,
                'id_lowongan' => null,
                'id_event' => null,
                'id_artikel' => null,
                'judul_header' => 'Slider Utama',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}