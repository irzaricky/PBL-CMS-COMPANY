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

        // Data untuk lowongan pekerjaan
        $jobTitles = [
            'Full Stack Developer',
            'UI/UX Designer',
            'Marketing Intern',
            'Backend Developer',
            'Frontend Developer',
            'Data Analyst',
            'DevOps Engineer',
            'Product Manager',
            'Quality Assurance',
            'System Analyst',
            'Project Manager',
            'Content Writer',
            'Social Media Specialist',
            'Mobile App Developer',
            'Network Administrator',
            'Database Administrator',
            'SEO Specialist',
            'Graphic Designer',
            'IT Support',
            'HR Manager'
        ];

        $jobTypes = ['Full-time', 'Part-time', 'Freelance', 'Internship'];

        $descriptions = [
            'Kami mencari %s dengan pengalaman minimal 2 tahun dalam bidangnya. Kandidat harus memiliki kemampuan analitis yang baik dan mampu bekerja dalam tim.',
            '%s dibutuhkan untuk mengembangkan solusi inovatif bagi perusahaan kami. Minimal pendidikan S1 di bidang terkait.',
            'Posisi %s terbuka untuk profesional yang memiliki passion dalam pengembangan teknologi dan produk digital.',
            'Dibutuhkan %s dengan keahlian teknis yang mumpuni dan kemampuan problem solving yang baik.',
            'Lowongan untuk %s bagi yang memiliki kreativitas tinggi dan mampu beradaptasi dengan perubahan teknologi.',
        ];

        // Generate 20 lowongan
        $lowonganData = [];
        $totalJobs = 20;

        for ($i = 1; $i <= $totalJobs; $i++) {
            $jobTitle = $jobTitles[$i - 1];
            $slug = Str::slug($jobTitle);
            $jobType = $jobTypes[array_rand($jobTypes)];
            $description = sprintf($descriptions[array_rand($descriptions)], $jobTitle);

            // Menentukan status - 20% terpublikasi, 80% tidak terpublikasi
            $status = rand(1, 100) <= 50 ? ContentStatus::TERPUBLIKASI->value : ContentStatus::TIDAK_TERPUBLIKASI->value;

            $lowonganData[] = [
                'id_lowongan' => $i,
                'id_user' => rand(1, 2),
                'judul_lowongan' => $jobTitle,
                'slug' => $slug,
                'deskripsi_pekerjaan' => $description,
                'jenis_lowongan' => $jobType,
                'gaji' => rand(5, 25) * 1000000, // 5-25 juta
                'tanggal_dibuka' => $tanggalDibuka = now()->subDays(rand(30, 180)),
                'tanggal_ditutup' => $tanggalDibuka->copy()->addDays(rand(30, 50)),
                'tenaga_dibutuhkan' => rand(1, 5),
                'thumbnail_lowongan' => $getRandomThumbnails(),
                'status_lowongan' => $status,
            ];
        }

        DB::table('lowongan')->insert($lowonganData);
    }
}
