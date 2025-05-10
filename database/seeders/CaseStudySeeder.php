<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Mitra;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CaseStudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get some random mitra IDs to associate with case studies
        $mitraIds = Mitra::inRandomOrder()->limit(3)->pluck('id_mitra')->toArray();

        // Create case studies
        $caseStudies = [
            [
                'judul_case_study' => 'Transformasi Digital PT. ABC',
                'deskripsi_case_study' => 'Bagaimana PT. ABC memanfaatkan teknologi untuk meningkatkan efisiensi operasional.',
                'isi_case_study' => '<h2>Latar Belakang</h2><p>PT. ABC menghadapi tantangan dalam meningkatkan efisiensi operasional mereka...</p><h2>Solusi</h2><p>Implementasi sistem ERP terintegrasi...</p><h2>Hasil</h2><p>Peningkatan efisiensi sebesar 30% dan penghematan biaya operasional.</p>',
            ],
            [
                'judul_case_study' => 'Optimasi SEO Website PT. XYZ',
                'deskripsi_case_study' => 'Strategi SEO yang meningkatkan trafik organik PT. XYZ hingga 200%.',
                'isi_case_study' => '<h2>Latar Belakang</h2><p>PT. XYZ memiliki website yang bagus tetapi trafik yang rendah...</p><h2>Strategi</h2><p>Implementasi teknik SEO on-page dan off-page...</p><h2>Hasil</h2><p>Peningkatan trafik organik sebesar 200% dalam 6 bulan.</p>',
            ],
            [
                'judul_case_study' => 'Pengembangan Aplikasi Mobile untuk Perusahaan Retail',
                'deskripsi_case_study' => 'Aplikasi mobile yang meningkatkan penjualan dan loyalitas pelanggan.',
                'isi_case_study' => '<h2>Latar Belakang</h2><p>Perusahaan retail ini ingin meningkatkan penjualan dan loyalitas pelanggan...</p><h2>Solusi</h2><p>Pengembangan aplikasi mobile dengan fitur loyalty points...</p><h2>Hasil</h2><p>Peningkatan penjualan sebesar 45% dalam satu tahun.</p>',
            ],
        ];

        foreach ($caseStudies as $caseStudy) {
            CaseStudy::create([
                'id_mitra' => $mitraIds[array_rand($mitraIds)],
                'judul_case_study' => $caseStudy['judul_case_study'],
                'slug_case_study' => Str::slug($caseStudy['judul_case_study']),
                'deskripsi_case_study' => $caseStudy['deskripsi_case_study'],
                'isi_case_study' => $caseStudy['isi_case_study'],
                'thumbnail_case_study' => null,
                'status_case_study' => $faker->randomElement(['terpublikasi', 'tidak terpublikasi']),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
