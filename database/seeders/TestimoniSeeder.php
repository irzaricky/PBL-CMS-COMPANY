<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('testimoni')->insert([
            [
                'id_testimoni' => 1,
                'id_user' => 1,
                'isi_testimoni' => 'Layanan yang sangat baik dan profesional. Sangat membantu dalam pengembangan sistem kami.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_testimoni' => 2,
                'id_user' => 2,
                'isi_testimoni' => 'Produk berkualitas dengan harga yang kompetitif. Sangat puas dengan fitur-fiturnya.',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_testimoni' => 3,
                'id_user' => 2,
                'isi_testimoni' => 'Respon cepat dan solusi tepat. Tim support sangat membantu menyelesaikan masalah.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}