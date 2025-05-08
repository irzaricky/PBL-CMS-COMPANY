<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Feedback::class;

    public function definition(): array
    {
        $hasResponse = $this->faker->boolean(70);

        return [
            'id_user' => User::whereIn('id_user', [9, 10, 11, 12, 13])->inRandomOrder()->first()?->id_user ?? rand(9, 13),
            'subjek_feedback' => $this->getRandomSubject(),
            'isi_feedback' => $this->generateFeedbackContent(),
            'tanggal_feedback' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'tanggapan_feedback' => $hasResponse ? $this->generateResponseContent() : null,
        ];
    }

    /**
     * Get a random subject for the feedback
     */
    private function getRandomSubject(): string
    {
        $subjects = [
            'Laporan Bug Aplikasi',
            'Peningkatan Kualitas Layanan',
            'Saran Perbaikan Website',
            'Saran Fitur Baru',
            'Perbaikan Antarmuka',
            'Masukan untuk Produk',
            'Laporan Error',
            'Pertanyaan tentang Layanan',
            'Kendala saat Registrasi',
            'Masalah Login',
            'Kritik untuk Sistem',
            'Kesan Penggunaan Aplikasi',
            'Permintaan Bantuan Teknis'
        ];

        return $this->faker->randomElement($subjects);
    }

    /**
     * Generate feedback content
     */
    private function generateFeedbackContent(): string
    {
        $types = ['question', 'suggestion', 'bug', 'complaint', 'praise'];
        $type = $this->faker->randomElement($types);

        switch ($type) {
            case 'question':
                return $this->faker->paragraph(1) . "\n\nMohon info lebih lanjut mengenai " .
                    $this->faker->randomElement(['fitur', 'layanan', 'produk', 'cara penggunaan']) .
                    " tersebut. Terima kasih.";

            case 'suggestion':
                return "Saya memiliki saran untuk " .
                    $this->faker->randomElement(['meningkatkan', 'menambahkan', 'memperbaiki']) .
                    " " . $this->faker->word . ".\n\n" . $this->faker->paragraph(2);

            case 'bug':
                return "Saya menemukan masalah pada " .
                    $this->faker->randomElement(['halaman', 'fitur', 'fungsi', 'sistem']) .
                    " " . $this->faker->word . ".\n\nKetika saya " .
                    $this->faker->sentence . ", yang terjadi adalah " .
                    $this->faker->sentence . ". Mohon perbaikannya.";

            case 'complaint':
                return $this->faker->paragraph(1) . "\n\nSaya kecewa dengan " .
                    $this->faker->randomElement(['layanan', 'respons', 'kualitas', 'kinerja']) .
                    " yang diberikan. Mohon ditindaklanjuti.";

            case 'praise':
                return "Saya sangat puas dengan " .
                    $this->faker->randomElement(['layanan', 'produk', 'fitur', 'dukungan']) .
                    " yang diberikan.\n\n" . $this->faker->paragraph(1);

            default:
                return $this->faker->paragraph(rand(1, 3));
        }
    }

    /**
     * Generate response content
     */
    private function generateResponseContent(): string
    {
        $responses = [
            "Terima kasih atas masukannya. Kami akan menindaklanjuti hal ini segera.",
            "Terima kasih atas laporan bugnya. Tim teknis kami sedang bekerja untuk memperbaikinya.",
            "Kami menghargai feedback yang Anda berikan. Saran Anda sudah kami teruskan ke tim pengembang.",
            "Mohon maaf atas ketidaknyamanannya. Kami sedang berusaha memperbaiki masalah tersebut.",
            "Terima kasih telah menghubungi kami. Untuk informasi lebih lanjut, silakan hubungi customer service kami di cs@biiscorp.com",
            "Feedback Anda sangat berharga bagi kami untuk terus meningkatkan layanan kami."
        ];

        return $this->faker->randomElement($responses) . "\n\n" .
            "Salam,\n" .
            $this->faker->randomElement(['Tim Support', 'Customer Service', 'Tim Teknis', 'Admin Biiscorp']);
    }
}