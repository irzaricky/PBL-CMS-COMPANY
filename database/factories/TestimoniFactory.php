<?php

namespace Database\Factories;

use App\Models\Testimoni;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimoni>
 */
class TestimoniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Testimoni::class;

    public function definition(): array
    {
        // Use users 9-13 for testimonials (test users)
        $userId = $this->faker->numberBetween(9, 13);

        return [
            'id_user' => $userId,
            'isi_testimoni' => $this->generateTestimonialContent(),
            'rating' => $this->faker->numberBetween(3, 5), // Mostly positive ratings between 3-5
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Generate testimonial content
     */
    private function generateTestimonialContent(): string
    {
        $testimonials = [
            // Positive technical testimonials
            "Layanan yang sangat profesional dan responsif. Tim teknis sangat membantu dalam menyelesaikan masalah kami.",
            "Produk berkualitas tinggi dengan dokumentasi yang lengkap. Sangat merekomendasikan untuk perusahaan yang membutuhkan solusi IT.",
            "Implementasi cepat dan efisien. Sistem bekerja persis seperti yang dijanjikan.",
            "Dukungan teknis yang luar biasa. Selalu tersedia bahkan di luar jam kerja saat kami menghadapi masalah kritis.",

            // Positive service testimonials
            "Sangat puas dengan pelayanan yang diberikan. Tim selalu ramah dan cepat menanggapi setiap pertanyaan.",
            "Komunikasi yang jelas dan profesional sepanjang proyek. Tidak ada kejutan dalam biaya atau jadwal.",
            "Fleksibel dalam menyesuaikan solusi sesuai kebutuhan spesifik bisnis kami.",
            "Pelatihan yang diberikan untuk tim kami sangat komprehensif dan mudah diikuti.",

            // Positive product specific testimonials
            "Dashboard analitik mereka mengubah cara kami mengambil keputusan bisnis. Sangat intuitif dan powerful.",
            "Sistem keamanan yang mereka implementasikan membuat data perusahaan kami jauh lebih aman tanpa mengorbankan aksesibilitas.",
            "Aplikasi mobile yang mereka kembangkan mendapatkan feedback positif dari pelanggan kami dalam 2 minggu pertama peluncuran.",
            "Solusi cloud yang ditawarkan sangat skalabel dan telah terbukti menghemat biaya operasional kami hingga 30%.",

            // General positive testimonials
            "Kerjasama yang sangat menyenangkan dan profesional. Akan bekerja sama lagi di proyek mendatang.",
            "Salah satu vendor terbaik yang pernah kami ajak bekerja sama. Sangat memahami kebutuhan bisnis kami.",
            "Produk dan layanan mereka melebihi ekspektasi kami. Sangat merekomendasikan!",
            "Tim yang sangat berpengetahuan dan berdedikasi. Mereka selalu up-to-date dengan teknologi terbaru.",

            // Longer testimonials
            "Kami telah bekerja sama dalam pengembangan sistem manajemen inventaris selama 6 bulan terakhir. Meskipun proyek kompleks dengan banyak integrasi, tim berhasil menyelesaikannya tepat waktu dengan kualitas yang luar biasa. Komunikasi selalu lancar dan transparan.",
            "Solusi yang ditawarkan tidak hanya memenuhi kebutuhan saat ini, tetapi juga mempertimbangkan pertumbuhan bisnis kami di masa depan. Tim teknis sangat kolaboratif dan responsif terhadap feedback. Implementasi berjalan lancar tanpa gangguan operasional yang signifikan.",
            "Sebagai perusahaan yang baru bertransformasi digital, kami sangat menghargai pendekatan step-by-step dan pelatihan komprehensif yang diberikan. Tidak hanya menyediakan sistem, tapi juga memastikan tim kami benar-benar mampu mengoperasikannya secara mandiri."
        ];

        return $this->faker->randomElement($testimonials);
    }
}