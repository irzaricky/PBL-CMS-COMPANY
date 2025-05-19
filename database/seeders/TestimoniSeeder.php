<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $testimoniTemplates = [
            'Layanan yang {kualitas} dan {sifat}. Sangat membantu dalam pengembangan {jenis} kami.',
            'Produk {kualitas} dengan harga yang {nilai}. Sangat {perasaan} dengan {aspek}nya.',
            'Respon {kecepatan} dan solusi {efektivitas}. Tim support sangat membantu menyelesaikan {masalah}.',
            'Saya sangat {perasaan} dengan kualitas {layanan} dan {aspek} produk yang ditawarkan. Perusahaan ini benar-benar memahami kebutuhan {jenis} kami.',
            'Aplikasi yang mereka kembangkan sangat {sifat} dan sesuai dengan {aspek} yang kami minta. Proses kerjasama yang {perasaan} dan {sifat}.',
            'Pengalaman menggunakan {produk} dari perusahaan ini sangat {perasaan}. Mereka selalu {tindakan} dengan cepat terhadap permintaan kami.',
            'Kami telah menggunakan {produk} selama {waktu} dan sangat {perasaan} dengan {aspek} yang ditawarkan.',
            'Tidak ada {masalah} selama menggunakan {produk} mereka. Tim {departemen} sangat {sifat} dan {efektivitas}.',
        ];

        $kualitas = ['sangat baik', 'baik sekali', 'berkualitas tinggi', 'luar biasa', 'istimewa', 'terjamin'];
        $sifat = ['profesional', 'terpercaya', 'handal', 'efisien', 'user-friendly', 'inovatif', 'komprehensif'];
        $jenis = ['sistem', 'bisnis', 'aplikasi', 'platform', 'website', 'proyek', 'infrastruktur'];
        $nilai = ['kompetitif', 'terjangkau', 'sesuai kualitas', 'ekonomis', 'sangat worth it'];
        $perasaan = ['puas', 'terkesan', 'senang', 'bangga', 'tidak kecewa', 'terkejut positif'];
        $aspek = ['fitur', 'kualitas', 'performa', 'dukungan teknis', 'keandalan', 'stabilitas', 'kemudahan penggunaan'];
        $kecepatan = ['cepat', 'tanggap', 'sigap', 'tepat waktu', 'tidak tertunda'];
        $efektivitas = ['tepat', 'efektif', 'akurat', 'sesuai kebutuhan', 'komprehensif'];
        $masalah = ['masalah', 'kendala', 'tantangan', 'kebutuhan', 'persoalan teknis'];
        $layanan = ['pelayanan', 'dukungan', 'bantuan teknis', 'konsultasi', 'implementasi'];
        $produk = ['aplikasi', 'sistem', 'layanan digital', 'platform', 'software', 'solusi teknologi'];
        $tindakan = ['merespon', 'menindaklanjuti', 'membantu', 'menanggapi', 'menyelesaikan'];
        $waktu = ['setahun', 'beberapa bulan', '6 bulan terakhir', 'kuartal ini', 'dua tahun'];
        $departemen = ['support', 'teknis', 'pengembangan', 'customer service', 'konsultan'];

        $testimonials = [];

        for ($i = 1; $i <= 15; $i++) {
            $template = $faker->randomElement($testimoniTemplates);

            $isi_testimoni = $template;
            $isi_testimoni = str_replace('{kualitas}', $faker->randomElement($kualitas), $isi_testimoni);
            $isi_testimoni = str_replace('{sifat}', $faker->randomElement($sifat), $isi_testimoni);
            $isi_testimoni = str_replace('{jenis}', $faker->randomElement($jenis), $isi_testimoni);
            $isi_testimoni = str_replace('{nilai}', $faker->randomElement($nilai), $isi_testimoni);
            $isi_testimoni = str_replace('{perasaan}', $faker->randomElement($perasaan), $isi_testimoni);
            $isi_testimoni = str_replace('{aspek}', $faker->randomElement($aspek), $isi_testimoni);
            $isi_testimoni = str_replace('{kecepatan}', $faker->randomElement($kecepatan), $isi_testimoni);
            $isi_testimoni = str_replace('{efektivitas}', $faker->randomElement($efektivitas), $isi_testimoni);
            $isi_testimoni = str_replace('{masalah}', $faker->randomElement($masalah), $isi_testimoni);
            $isi_testimoni = str_replace('{layanan}', $faker->randomElement($layanan), $isi_testimoni);
            $isi_testimoni = str_replace('{produk}', $faker->randomElement($produk), $isi_testimoni);
            $isi_testimoni = str_replace('{tindakan}', $faker->randomElement($tindakan), $isi_testimoni);
            $isi_testimoni = str_replace('{waktu}', $faker->randomElement($waktu), $isi_testimoni);
            $isi_testimoni = str_replace('{departemen}', $faker->randomElement($departemen), $isi_testimoni);

            $createdAt = $faker->dateTimeBetween('-6 months', 'now');

            $testimonials[] = [
                'id_testimoni' => $i,
                'id_user' => $faker->numberBetween(9, 13),
                'isi_testimoni' => $isi_testimoni,
                'rating' => $faker->numberBetween(3, 5),
                'status' => $faker->randomElement([ContentStatus::TERPUBLIKASI->value, ContentStatus::TIDAK_TERPUBLIKASI->value]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('testimoni')->insert($testimonials);
    }
}