<?php

namespace Database\Factories;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artikel>
 */
class ArtikelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Artikel::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->bs();

        return [
            'id_kategori_artikel' => KategoriArtikel::inRandomOrder()->first()?->id_kategori_artikel ?? rand(1, 3),
            'id_user' => User::whereIn('id_user', [3, 4, 5])->inRandomOrder()->first()?->id_user ?? rand(3, 5),
            'judul_artikel' => $title,
            'konten_artikel' => $this->generateArtikelContent(),
            'jumlah_view' => $this->faker->numberBetween(100, 10000),
            'slug' => Str::slug($title),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Generate artikel content dengan struktur HTML
     */
    private function generateArtikelContent(): string
    {
        $content = '<h2>' . $this->faker->sentence(rand(4, 8)) . '</h2>';
        $content .= '<p>' . $this->faker->paragraph(rand(20, 40)) . '</p>';

        // Section 1
        $content .= '<h3>' . $this->faker->sentence(rand(3, 6)) . '</h3>';
        $content .= '<p>' . $this->faker->paragraph(rand(20, 30)) . '</p>';

        // List items
        $content .= '<ul>';
        for ($i = 0; $i < rand(3, 5); $i++) {
            $content .= '<li><strong>' . $this->faker->words(rand(2, 4), true) . '</strong><br>';
            $content .= $this->faker->sentence(rand(5, 10)) . '</li>';
        }
        $content .= '</ul>';

        // Section 2
        $content .= '<h3>' . $this->faker->sentence(rand(3, 6)) . '</h3>';
        $content .= '<p>' . $this->faker->paragraph(rand(10, 20)) . '</p>';

        // Blockquote
        $content .= '<blockquote>"' . $this->faker->sentence(rand(10, 15)) . '"<br>&nbsp;— <em>' . $this->faker->name . ', ' . $this->faker->jobTitle . '</em></blockquote>';

        // Penutup
        $content .= '<h3>' . $this->faker->sentence(rand(3, 6)) . '</h3>';
        $content .= '<p>' . $this->faker->paragraph(rand(3, 5)) . '</p>';

        return $content;
    }
}