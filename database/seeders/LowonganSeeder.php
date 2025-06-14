<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Faker\Factory as Faker;
use Carbon\Carbon;

class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $fakerEN = Faker::create('en_US');

        // Ambil 5 user pertama dari tabel users menggunakan User model
        try {
            $users = \App\Models\User::orderBy('id_user')->limit(5)->pluck('id_user')->toArray();

            // Jika tidak ada user, skip seeding
            if (empty($users)) {
                $this->command->warn('No users found in the database. Skipping lowongan seeding.');
                return;
            }

            // Validasi bahwa semua user ID yang diambil valid
            $validUsers = \App\Models\User::whereIn('id_user', $users)->pluck('id_user')->toArray();
            if (count($validUsers) !== count($users)) {
                $this->command->warn('Some users not found, using only valid users for lowongan seeding.');
                $users = $validUsers;
            }

            if (empty($users)) {
                $this->command->warn('No valid users found. Skipping lowongan seeding.');
                return;
            }

        } catch (\Exception $e) {
            $this->command->error('Error accessing users table: ' . $e->getMessage());
            return;
        }

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

        // Generate 20 lowongan
        $lowonganData = [];
        $totalJobs = 20;

        for ($i = 1; $i <= $totalJobs; $i++) {
            $jobTitle = $jobTitles[$i - 1];

            // Generate slug and check for duplicates
            $baseSlug = Str::slug($jobTitle);
            $slug = $baseSlug;
            $counter = 1;

            // Check if slug exists in current batch or database
            while (
                collect($lowonganData)->contains('slug', $slug) ||
                DB::table('lowongan')->where('slug', $slug)->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $jobType = $jobTypes[array_rand($jobTypes)];

            // Generate rich HTML description
            $description = $this->generateJobDescription($faker, $fakerEN, $jobTitle, $jobType);

            // Menentukan status - 50% terpublikasi, 50% tidak terpublikasi
            $status = rand(1, 100) <= 50 ? ContentStatus::TERPUBLIKASI->value : ContentStatus::TIDAK_TERPUBLIKASI->value;

            $tanggalDibuka = Carbon::now()->subDays(rand(30, 180));

            $lowonganData[] = [
                'id_lowongan' => $i,
                'id_user' => $faker->randomElement($users), // Ambil random dari 5 user pertama
                'judul_lowongan' => $jobTitle,
                'slug' => $slug,
                'deskripsi_pekerjaan' => $description,
                'jenis_lowongan' => $jobType,
                'gaji' => rand(5, 25) * 1000000, // 5-25 juta
                'tanggal_dibuka' => $tanggalDibuka,
                'tanggal_ditutup' => $tanggalDibuka->copy()->addDays(rand(30, 50)),
                'tenaga_dibutuhkan' => rand(1, 5),
                'thumbnail_lowongan' => $getRandomThumbnails(),
                'status_lowongan' => $status,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        DB::table('lowongan')->insert($lowonganData);
    }

    /**
     * Generate job description dengan struktur HTML yang kaya
     * @param \Faker\Generator $faker
     * @param \Faker\Generator $fakerEN
     * @param string $jobTitle
     * @param string $jobType
     * @return string
     */
    private function generateJobDescription($faker, $fakerEN, $jobTitle, $jobType)
    {
        // Opening section dengan deskripsi perusahaan
        $description = '<h2>Tentang Posisi Ini</h2>';
        $description .= '<p>' . $faker->paragraph(rand(10, 15)) . '</p>';

        // Company overview
        $description .= '<h3>Tentang Perusahaan</h3>';
        $description .= '<p>Kami adalah perusahaan ' . $fakerEN->bs() . ' yang berkembang pesat dan berkomitmen untuk memberikan solusi terbaik bagi klien. Dengan tim yang solid dan budaya kerja yang dinamis, kami terus berinovasi dalam industri teknologi.</p>';

        // Job Description Section
        $description .= '<h3>Deskripsi Pekerjaan</h3>';
        $description .= '<p>Sebagai <strong>' . $jobTitle . '</strong>, Anda akan bertanggung jawab untuk:</p>';

        $responsibilities = $this->getJobResponsibilities($jobTitle);
        $description .= '<ul>';
        foreach ($responsibilities as $responsibility) {
            $description .= '<li>' . $responsibility . '</li>';
        }
        $description .= '</ul>';

        // Requirements Section
        $description .= '<h3>Kualifikasi & Persyaratan</h3>';
        $requirements = $this->getJobRequirements($jobTitle, $jobType);
        $description .= '<ul>';
        foreach ($requirements as $requirement) {
            $description .= '<li>' . $requirement . '</li>';
        }
        $description .= '</ul>';

        // Preferred Qualifications
        if ($faker->boolean(80)) {
            $description .= '<h3>Kualifikasi Tambahan (Nice to Have)</h3>';
            $preferred = $this->getPreferredQualifications($jobTitle);
            $description .= '<ul>';
            foreach ($preferred as $pref) {
                $description .= '<li>' . $pref . '</li>';
            }
            $description .= '</ul>';
        }

        // Benefits Section dengan styling menarik
        $description .= '<h3>Benefit & Fasilitas</h3>';
        $description .= '<div style="background-color: #f8f9fa; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0;">';
        $description .= '<p style="margin-bottom: 15px;"><strong>Kami menawarkan paket kompensasi dan benefit yang menarik:</strong></p>';

        $benefits = [
            '💰 Gaji kompetitif sesuai pengalaman dan kemampuan',
            '🏥 Asuransi kesehatan untuk karyawan dan keluarga',
            '📚 Program training dan pengembangan karir',
            '🏖️ Cuti tahunan dan cuti fleksibel',
            '🍕 Makan siang gratis dan snack di kantor',
            '🚗 Tunjangan transportasi atau parkir',
            '💻 Laptop dan peralatan kerja terbaru',
            '🎉 Bonus kinerja dan THR',
            '🏠 Work from home flexibility',
            '⚡ Internet allowance untuk WFH',
            '🎯 Performance bonus dan career advancement',
            '🎪 Team building dan company outing'
        ];

        $selectedBenefits = $faker->randomElements($benefits, rand(6, 9));
        $description .= '<ul style="margin-bottom: 0;">';
        foreach ($selectedBenefits as $benefit) {
            $description .= '<li style="margin-bottom: 8px;">' . $benefit . '</li>';
        }
        $description .= '</ul>';
        $description .= '</div>';

        // Work Environment Section
        if ($faker->boolean(75)) {
            $description .= '<h3>Lingkungan Kerja</h3>';
            $workEnvs = [
                'Tim yang kolaboratif dan suportif',
                'Budaya kerja yang fleksibel dan adaptif',
                'Kantor modern dengan fasilitas lengkap',
                'Open communication dengan management',
                'Kesempatan untuk mengembangkan skill dan karir'
            ];

            $description .= '<ul>';
            foreach ($faker->randomElements($workEnvs, rand(3, 5)) as $env) {
                $description .= '<li>' . $env . '</li>';
            }
            $description .= '</ul>';
        }

        // Career Path
        if ($faker->boolean(70)) {
            $description .= '<h3>Jenjang Karir</h3>';
            $description .= '<p>Kami berkomitmen untuk mengembangkan karir karyawan dengan jalur yang jelas:</p>';
            $description .= '<div style="background-color: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 15px; margin: 15px 0;">';

            $careerPaths = $this->getCareerPath($jobTitle);
            $description .= '<p style="margin-bottom: 10px;"><strong>Possible Career Progression:</strong></p>';
            $description .= '<p style="margin-bottom: 0; color: #0066cc;">';
            $description .= implode(' → ', $careerPaths);
            $description .= '</p>';
            $description .= '</div>';
        }

        // Application Process
        $description .= '<h3>Proses Seleksi</h3>';
        $description .= '<div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0;">';
        $description .= '<h4 style="color: #856404; margin-top: 0;">📋 Tahapan Seleksi:</h4>';
        $description .= '<ol style="color: #856404; margin-bottom: 15px;">';
        $description .= '<li>Screening CV dan portfolio</li>';
        $description .= '<li>Interview HR via phone/video call</li>';
        $description .= '<li>Technical test/assessment</li>';
        $description .= '<li>Interview dengan user/manager</li>';
        $description .= '<li>Final interview dan negosiasi offer</li>';
        $description .= '</ol>';
        $description .= '<p style="color: #856404; margin-bottom: 0;"><em>💡 Proses seleksi memakan waktu 1-2 minggu</em></p>';
        $description .= '</div>';

        // Call to Action
        $description .= '<h3>Siap Bergabung dengan Tim Kami?</h3>';
        $description .= '<div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 20px; margin: 20px 0;">';
        $description .= '<p style="color: #0c5460; margin-bottom: 15px;"><strong>🚀 Jangan lewatkan kesempatan emas ini!</strong></p>';
        $description .= '<p style="color: #0c5460; margin-bottom: 15px;">Kirimkan CV terbaru Anda beserta portfolio (jika ada) melalui tombol "Lamar Sekarang" di atas.</p>';
        $description .= '<p style="color: #0c5460; margin-bottom: 0;"><em>Hanya kandidat yang memenuhi kualifikasi yang akan kami hubungi untuk tahap selanjutnya.</em></p>';
        $description .= '</div>';

        // Contact Information
        if ($faker->boolean(60)) {
            $description .= '<h3>Informasi Kontak</h3>';
            $description .= '<div style="background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0;">';
            $description .= '<p style="margin-bottom: 10px;"><strong>Ada pertanyaan tentang posisi ini?</strong></p>';
            $description .= '<ul style="margin-bottom: 0;">';
            $description .= '<li>📧 Email: recruitment@company.com</li>';
            $description .= '<li>📱 WhatsApp: +62 812-3456-7890</li>';
            $description .= '<li>🕐 Jam kerja: Senin-Jumat, 09:00-17:00 WIB</li>';
            $description .= '</ul>';
            $description .= '</div>';
        }

        return $description;
    }

    /**
     * Generate job responsibilities based on job title
     */
    private function getJobResponsibilities($jobTitle)
    {
        $responsibilities = [
            'Full Stack Developer' => [
                'Mengembangkan aplikasi web end-to-end menggunakan teknologi modern',
                'Merancang dan mengimplementasikan database yang efisien',
                'Berkolaborasi dengan tim design untuk implementasi UI/UX',
                'Melakukan testing dan debugging aplikasi',
                'Mengoptimalkan performa aplikasi untuk user experience terbaik'
            ],
            'UI/UX Designer' => [
                'Merancang wireframe dan prototype untuk aplikasi/website',
                'Melakukan user research dan usability testing',
                'Membuat design system yang konsisten',
                'Berkolaborasi dengan developer untuk implementasi design',
                'Menganalisis user behavior dan melakukan iterasi design'
            ],
            'Backend Developer' => [
                'Mengembangkan dan maintain server-side aplikasi',
                'Merancang dan mengoptimalkan database',
                'Membuat API dan web services',
                'Mengimplementasikan security measures',
                'Melakukan performance tuning dan monitoring'
            ],
            'Frontend Developer' => [
                'Mengembangkan user interface yang responsive dan interaktif',
                'Mengintegrasikan frontend dengan backend APIs',
                'Mengoptimalkan website performance dan SEO',
                'Memastikan cross-browser compatibility',
                'Mengimplementasikan modern JavaScript frameworks'
            ]
        ];

        return $responsibilities[$jobTitle] ?? [
            'Menjalankan tugas sesuai dengan job description',
            'Berkolaborasi dengan tim untuk mencapai target perusahaan',
            'Melakukan improvement dan inovasi dalam pekerjaan',
            'Membuat laporan berkala terkait progress pekerjaan',
            'Mengikuti standar dan prosedur perusahaan'
        ];
    }

    /**
     * Generate job requirements based on job title and type
     */
    private function getJobRequirements($jobTitle, $jobType)
    {
        $baseRequirements = [
            'Pendidikan minimal S1 atau sederajat',
            'Pengalaman kerja ' . ($jobType === 'Internship' ? '0-1 tahun' : '2-4 tahun') . ' di bidang terkait',
            'Kemampuan komunikasi yang baik dalam bahasa Indonesia dan Inggris',
            'Mampu bekerja dalam tim dan individu',
            'Memiliki motivasi tinggi dan kemampuan problem solving'
        ];

        $specificRequirements = [
            'Full Stack Developer' => [
                'Menguasai JavaScript, HTML, CSS, dan framework modern (React/Vue/Angular)',
                'Pengalaman dengan backend technologies (Node.js, Python, PHP)',
                'Familiar dengan database (MySQL, PostgreSQL, MongoDB)',
                'Pengalaman dengan version control (Git)',
                'Memahami RESTful API dan GraphQL'
            ],
            'UI/UX Designer' => [
                'Menguasai design tools (Figma, Sketch, Adobe XD)',
                'Memahami design principles dan user-centered design',
                'Portfolio yang menunjukkan skill design',
                'Pengalaman dengan prototyping dan wireframing',
                'Familiar dengan HTML/CSS dasar'
            ]
        ];

        $requirements = array_merge($baseRequirements, $specificRequirements[$jobTitle] ?? [
            'Memiliki pengetahuan di bidang ' . strtolower($jobTitle),
            'Mampu menggunakan tools yang relevan dengan pekerjaan',
            'Berpengalaman dalam project management',
            'Memiliki sertifikasi terkait (jika ada)'
        ]);

        return $requirements;
    }

    /**
     * Generate preferred qualifications
     */
    private function getPreferredQualifications($jobTitle)
    {
        return [
            'Memiliki sertifikasi profesional di bidang terkait',
            'Pengalaman dengan metodologi Agile/Scrum',
            'Kontribusi pada open source projects',
            'Pengalaman mentoring atau leadership',
            'Familiar dengan cloud platforms (AWS, GCP, Azure)'
        ];
    }

    /**
     * Generate career path progression
     */
    private function getCareerPath($jobTitle)
    {
        $careerPaths = [
            'Full Stack Developer' => ['Junior Developer', 'Senior Developer', 'Tech Lead', 'Engineering Manager'],
            'UI/UX Designer' => ['Junior Designer', 'Senior Designer', 'Lead Designer', 'Design Manager'],
            'Backend Developer' => ['Junior Developer', 'Senior Developer', 'Backend Architect', 'CTO'],
            'Frontend Developer' => ['Junior Developer', 'Senior Developer', 'Frontend Architect', 'Engineering Manager']
        ];

        return $careerPaths[$jobTitle] ?? ['Junior Level', 'Senior Level', 'Team Lead', 'Manager'];
    }
}