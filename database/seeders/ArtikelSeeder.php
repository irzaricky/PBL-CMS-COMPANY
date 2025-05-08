<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $artikels = [];
        $usedSlugs = [];


        for ($i = 1; $i <= 100; $i++) {
            // Judul artikel
            $judul = Faker::create('en_US')->unique()->bs();

            // Generate konten HTML
            $konten = $this->generateArtikelContent($faker);

            $artikels[] = [
                'id_artikel' => $i,
                'id_kategori_artikel' => rand(1, 3),
                'id_user' => rand(3, 5),
                'judul_artikel' => $judul,
                'konten_artikel' => $konten,
                'jumlah_view' => $faker->numberBetween(100, 10000),
                'slug' => Str::slug($judul),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        // Masukkan semua data ke database
        foreach (array_chunk($artikels, 100) as $chunk) {
            DB::table('artikel')->insert($chunk);
        }
    }

    /**
     * Generate artikel content dengan struktur HTML
     */
    private function generateArtikelContent($faker)
    {
        $content = '<h2>' . $faker->sentence(rand(4, 8)) . '</h2>';
        $content .= '<p>' . $faker->paragraph(rand(20, 40)) . '</p>';

        // Section 1
        $content .= '<h3>' . $faker->sentence(rand(3, 6)) . '</h3>';
        $content .= '<p>' . $faker->paragraph(rand(20, 30)) . '</p>';

        // List items
        $content .= '<ul>';
        for ($i = 0; $i < rand(3, 5); $i++) {
            $content .= '<li><strong>' . $faker->words(rand(2, 4), true) . '</strong><br>';
            $content .= $faker->sentence(rand(5, 10)) . '</li>';
        }
        $content .= '</ul>';

        // Section 2
        $content .= '<h3>' . $faker->sentence(rand(3, 6)) . '</h3>';
        $content .= '<p>' . $faker->paragraph(rand(10, 20)) . '</p>';

        // Blockquote
        $content .= '<blockquote>"' . $faker->sentence(rand(10, 15)) . '"<br>&nbsp;— <em>' . $faker->name . ', ' . $faker->jobTitle . '</em></blockquote>';

        // Penutup
        $content .= '<h3>' . $faker->sentence(rand(3, 6)) . '</h3>';
        $content .= '<p>' . $faker->paragraph(rand(3, 5)) . '</p>';

        return $content;
    }
}