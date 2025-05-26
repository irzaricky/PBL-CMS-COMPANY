<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        // Proses copy image
        $sourcePath = database_path('seeders/seeder_image/');
        $targetPath = 'lowongan-thumbnails';
        Storage::disk('public')->makeDirectory($targetPath);
        $files = array_values(array_filter(scandir($sourcePath), fn($f) => !in_array($f, ['.', '..'])));

        // Fungsi untuk ambil 1–3 gambar acak
        $getRandomThumbnails = function () use ($files, $sourcePath, $targetPath) {
            $thumbnails = [];
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $originalFile = $files[array_rand($files)];
                $newFileName = Str::random(10) . '-' . $originalFile;
                Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $newFileName);
                $thumbnails[] = $targetPath . '/' . $newFileName;
            }
            return json_encode($thumbnails);
        };

        DB::table('lowongan')->insert([
            [
                'id_lowongan' => 1,
                'id_user' => 1,
                'judul_lowongan' => 'Full Stack Developer',
                'slug' => 'full-stack-developer',
                'deskripsi_pekerjaan' => 'Kami mencari Full Stack Developer dengan pengalaman minimal 2 tahun dalam pengembangan web menggunakan Laravel dan Vue.js.',
                'jenis_lowongan' => 'Full-time',
                'gaji' => 12000000,
                'tanggal_dibuka' => now(),
                'tanggal_ditutup' => now()->addDays(30),
                'tenaga_dibutuhkan' => 2,
                'thumbnail_lowongan' => $getRandomThumbnails(),
                'status_lowongan' => ContentStatus::TERPUBLIKASI->value,
            ],
            [
                'id_lowongan' => 2,
                'id_user' => 1,
                'judul_lowongan' => 'UI/UX Designer',
                'slug' => 'ui-ux-designer',
                'deskripsi_pekerjaan' => 'Mencari UI/UX Designer dengan keahlian dalam Figma, Adobe XD, dan pemahaman tentang user experience.',
                'jenis_lowongan' => 'Full-time',
                'gaji' => 10000000,
                'tanggal_dibuka' => now(),
                'tanggal_ditutup' => now()->addDays(14),
                'tenaga_dibutuhkan' => 1,
                'thumbnail_lowongan' => $getRandomThumbnails(),
                'status_lowongan' => ContentStatus::TERPUBLIKASI->value,
            ],
            [
                'id_lowongan' => 3,
                'id_user' => 2,
                'judul_lowongan' => 'Marketing Intern',
                'slug' => 'marketing-intern',
                'deskripsi_pekerjaan' => 'Program magang untuk mahasiswa semester akhir jurusan Marketing atau Komunikasi.',
                'jenis_lowongan' => 'Internship',
                'gaji' => 2500000,
                'tanggal_dibuka' => now(),
                'tanggal_ditutup' => now()->addDays(7),
                'tenaga_dibutuhkan' => 3,
                'thumbnail_lowongan' => $getRandomThumbnails(),
                'status_lowongan' => ContentStatus::TERPUBLIKASI->value,
            ],
        ]);
    }
}
