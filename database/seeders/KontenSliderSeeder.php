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
                'durasi_slider' => 5,
                'id_galeri' => 1,
                'id_produk' => 1,
                'id_event' => 1,
                'id_artikel' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}