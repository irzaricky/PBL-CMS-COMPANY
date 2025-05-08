<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('testimoni')->insert([
            [
                'id_testimoni' => 1,
                'id_user' => 9, // Test User 1
                'isi_testimoni' => 'Layanan yang sangat baik dan profesional. Sangat membantu dalam pengembangan sistem kami.',
                'rating' => 5,
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            [
                'id_testimoni' => 2,
                'id_user' => 10, // Test User 2
                'isi_testimoni' => 'Produk berkualitas dengan harga yang kompetitif. Sangat puas dengan fitur-fiturnya.',
                'rating' => 4,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'id_testimoni' => 3,
                'id_user' => 11, // Test User 3
                'isi_testimoni' => 'Respon cepat dan solusi tepat. Tim support sangat membantu menyelesaikan masalah.',
                'rating' => 5,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'id_testimoni' => 4,
                'id_user' => 12, // Test User 4
                'isi_testimoni' => 'Saya sangat terkesan dengan kualitas pelayanan dan keandalan produk yang ditawarkan. Perusahaan ini benar-benar memahami kebutuhan bisnis kami.',
                'rating' => 5,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_testimoni' => 5,
                'id_user' => 13, // Test User 5
                'isi_testimoni' => 'Aplikasi yang mereka kembangkan sangat user-friendly dan sesuai dengan spesifikasi yang kami minta. Proses kerjasama yang menyenangkan dan profesional.',
                'rating' => 4,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ]);
    }
}