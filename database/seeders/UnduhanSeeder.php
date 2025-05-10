<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnduhanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $unduhan = [];

        // Dokumen templates dan nama file
        $dokumenTemplates = [
            'Panduan Pengguna' => ['extension' => 'pdf', 'description' => 'Dokumen panduan untuk pengguna aplikasi perusahaan'],
            'Formulir Pendaftaran' => ['extension' => 'docx', 'description' => 'Formulir untuk pendaftaran layanan atau keanggotaan'],
            'Laporan Tahunan' => ['extension' => 'pdf', 'description' => 'Laporan keuangan dan kinerja perusahaan tahunan'],
            'Ketentuan Layanan' => ['extension' => 'pdf', 'description' => 'Dokumen berisi ketentuan penggunaan layanan'],
            'Panduan Instalasi' => ['extension' => 'pdf', 'description' => 'Petunjuk instalasi aplikasi dan konfigurasi awal'],
            'Template Proposal' => ['extension' => 'docx', 'description' => 'Template untuk pembuatan proposal kerjasama'],
            'Brosur Produk' => ['extension' => 'pdf', 'description' => 'Informasi detail tentang produk dan layanan'],
            'Manual Pengoperasian' => ['extension' => 'pdf', 'description' => 'Panduan pengoperasian perangkat atau sistem'],
            'Checklist Quality Assurance' => ['extension' => 'xlsx', 'description' => 'Daftar cek untuk menjamin kualitas produk'],
            'Whitepaper Teknis' => ['extension' => 'pdf', 'description' => 'Dokumen teknis mengenai teknologi yang digunakan'],
            'Presentasi Perusahaan' => ['extension' => 'pptx', 'description' => 'Slide presentasi tentang profil perusahaan'],
            'Katalog Layanan' => ['extension' => 'pdf', 'description' => 'Katalog lengkap layanan yang ditawarkan'],
            'Update Sistem' => ['extension' => 'zip', 'description' => 'Pembaruan sistem untuk perangkat lunak'],
            'Sertifikat Keamanan' => ['extension' => 'pdf', 'description' => 'Dokumen sertifikasi keamanan sistem'],
            'FAQ' => ['extension' => 'pdf', 'description' => 'Kumpulan pertanyaan yang sering ditanyakan'],
            'Template Dokumen' => ['extension' => 'docx', 'description' => 'Template standar untuk dokumen perusahaan'],
            'Ebook Edukasi' => ['extension' => 'pdf', 'description' => 'Materi edukasi tentang teknologi terbaru'],
            'Changelog' => ['extension' => 'txt', 'description' => 'Catatan perubahan pada versi terbaru aplikasi'],
            'Standar Operasional' => ['extension' => 'pdf', 'description' => 'Standar prosedur operasional perusahaan'],
            'Rencana Strategis' => ['extension' => 'pdf', 'description' => 'Dokumen rencana strategis jangka panjang']
        ];

        $keys = array_keys($dokumenTemplates);

        // Generate 20 unduhan dengan Faker
        for ($i = 1; $i <= 20; $i++) {
            $type = $keys[$i - 1];
            $template = $dokumenTemplates[$type];
            $year = $faker->year('now');
            $fileName = Str::slug($type) . "-" . $year . "." . $template['extension'];
            $categoryId = $faker->numberBetween(1, 3); // Categories: 1=Dokumen, 2=Formulir, 3=Panduan
            $isPublished = $faker->boolean(70);
            $createdAt = $faker->dateTimeBetween('-2 years', 'now');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            $unduhan[] = [
                'id_unduhan' => $i,
                'id_kategori_unduhan' => $categoryId,
                'id_user' => $faker->numberBetween(1, 7), // Assuming 7 users (admin, director, content management users)
                'nama_unduhan' => $type . ' ' . $year,
                'slug' => Str::slug($type . ' ' . $year),
                'lokasi_file' => 'unduhan-files/' . $fileName,
                'deskripsi' => $template['description'] . '. ' . $faker->sentence(rand(8, 15)),
                'jumlah_unduhan' => $faker->numberBetween(10, 2000),
                'status_unduhan' => $isPublished ? ContentStatus::TERPUBLIKASI->value : ContentStatus::TIDAK_TERPUBLIKASI->value,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'deleted_at' => null,
            ];
        }

        DB::table('unduhan')->insert($unduhan);
    }
}