<?php

namespace Database\Seeders;

use Illuminate\Http\File;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan faker default (en_US) untuk bs atau catchprase
        $fakerEN = Faker::create('en_US');
        // Gunakan faker Indonesia untuk data lainnya
        $faker = Faker::create('id_ID');

        $events = [];

        // bagian proses image
        $sourcePath = database_path('seeders/seeder_image/');
        $targetPath = 'event-thumbnails';

        // Pastikan folder target ada
        Storage::disk('public')->makeDirectory($targetPath);

        // Ambil semua file di folder seeder_image
        $files = array_values(array_filter(scandir($sourcePath), fn($f) => !in_array($f, ['.', '..'])));

        // Generate 20 event dengan Faker
        for ($i = 1; $i <= 20; $i++) {
            $namaEvent = $fakerEN->bs();
            $startDate = $faker->dateTimeBetween('-2 months', '+6 months');
            $endDate = clone $startDate;
            $duration = rand(1, 8); // Durasi dalam jam
            $endDate->modify("+$duration hours");

            // 30% event daring, 70% luring
            $isOnline = $faker->boolean(30);

            if ($isOnline) {
                $lokasi = 'Online via ' . $faker->randomElement(['Zoom', 'Google Meet', 'Microsoft Teams', 'Webex']);
            } else {
                $lokasi = $faker->company() . ', ' . $faker->city();
            }

            // Generate array untuk menyimpan multiple images
            $images = [];

            // Tentukan jumlah gambar untuk event ini (1-3 gambar)
            $imageCount = rand(1, 3);

            // Pilih dan proses beberapa gambar
            for ($j = 0; $j < $imageCount; $j++) {
                // Pilih gambar random
                $originalFile = $files[array_rand($files)];

                // Buat nama baru biar unik
                $newFileName = Str::random(10) . '-' . $originalFile;

                // Copy ke storage
                Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $newFileName);

                // Tambahkan path gambar ke array images
                $images[] = $targetPath . '/' . $newFileName;
            }

            // Generate deskripsi HTML yang kaya
            $deskripsi = $this->generateEventDescription($faker, $fakerEN, $images);

            // Generate slug and check for duplicates
            $baseSlug = Str::slug($namaEvent);
            $slug = $baseSlug;
            $counter = 1;

            // Check if slug exists in current batch or database
            while (
                collect($events)->contains('slug', $slug) ||
                DB::table('event')->where('slug', $slug)->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $events[] = [
                'id_event' => $i,
                'nama_event' => ucfirst($namaEvent),
                'deskripsi_event' => $deskripsi,
                'thumbnail_event' => json_encode($images),
                'lokasi_event' => $lokasi,
                'link_lokasi_event' => 'https://maps.app.goo.gl/TkgARfqzGjPkxFRcA',
                'waktu_start_event' => $startDate,
                'waktu_end_event' => $endDate,
                'jumlah_pendaftar' => rand(0, 200),
                'slug' => $slug,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        DB::table('event')->insert($events);
    }

    /**
     * Generate deskripsi event dengan struktur HTML yang kaya
     * @param \Faker\Generator $faker
     * @param \Faker\Generator $fakerEN
     * @param array $images Array of image paths to include in content
     * @return string
     */
    private function generateEventDescription($faker, $fakerEN, $images = [])
    {
        // Opening section dengan deskripsi utama
        $deskripsi = '<h2>' . $fakerEN->sentence(rand(4, 8)) . '</h2>';
        $deskripsi .= '<p>' . $faker->paragraph(rand(15, 25)) . '</p>';

        // Tentukan apakah event online atau offline untuk konteks
        $isOnline = $faker->boolean(30);
        
        // Section Overview
        $deskripsi .= '<h3>Overview Event</h3>';
        $deskripsi .= '<p>' . $faker->paragraph(rand(10, 15)) . '</p>';

        // Tambahkan blockquote dengan quote inspiratif
        if ($faker->boolean(80)) {
            $quotes = [
                'Innovation distinguishes between a leader and a follower.',
                'The future belongs to those who believe in the beauty of their dreams.',
                'Success is not final, failure is not fatal: it is the courage to continue that counts.',
                'The only way to do great work is to love what you do.',
                'Learning never exhausts the mind.'
            ];
            $selectedQuote = $faker->randomElement($quotes);
            $deskripsi .= '<blockquote>"' . $selectedQuote . '"<br>&nbsp;— <em>' . $faker->name . ', ' . $faker->jobTitle . '</em></blockquote>';
        }

        // Section Pembicara dengan styling yang menarik
        if ($faker->boolean(85)) {
            $deskripsi .= '<h3>Pembicara Terbaik</h3>';
            $deskripsi .= '<p>Event ini akan dipandu oleh para ahli terbaik di bidangnya:</p>';
            $deskripsi .= '<ul>';
            for ($i = 0; $i < rand(2, 4); $i++) {
                $deskripsi .= '<li><strong>' . $faker->name() . '</strong><br>';
                $deskripsi .= '<em>' . $faker->jobTitle() . ' di ' . $faker->company() . '</em><br>';
                $deskripsi .= $faker->sentence(rand(8, 12)) . '</li>';
            }
            $deskripsi .= '</ul>';
        }

        // Section Agenda dengan format yang rapi
        if ($faker->boolean(90)) {
            $deskripsi .= '<h3>Agenda Kegiatan</h3>';
            $deskripsi .= '<p>Berikut adalah rundown acara yang telah kami persiapkan untuk Anda:</p>';
            
            $deskripsi .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
            $deskripsi .= '<thead>';
            $deskripsi .= '<tr style="background-color: #f8f9fa;">';
            $deskripsi .= '<th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;"><strong>Waktu</strong></th>';
            $deskripsi .= '<th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;"><strong>Kegiatan</strong></th>';
            $deskripsi .= '</tr>';
            $deskripsi .= '</thead>';
            $deskripsi .= '<tbody>';
            
            $startHour = rand(8, 10);
            for ($i = 0; $i < rand(4, 7); $i++) {
                $duration = rand(30, 120); // durasi dalam menit
                $endHour = $startHour;
                $endMinute = $duration;
                if ($endMinute >= 60) {
                    $endHour += intval($endMinute / 60);
                    $endMinute = $endMinute % 60;
                }
                
                $activities = [
                    'Registration & Welcome Coffee',
                    'Keynote Speech',
                    'Panel Discussion',
                    'Workshop Session',
                    'Networking Break',
                    'Q&A Session',
                    'Closing Ceremony'
                ];
                
                $activity = $i === 0 ? 'Registration & Welcome Coffee' : 
                           ($i === count(range(0, rand(4, 7))) - 1 ? 'Closing Ceremony' : 
                           $faker->randomElement($activities));
                
                $deskripsi .= '<tr>';
                $deskripsi .= '<td style="border: 1px solid #dee2e6; padding: 12px;">' . 
                             sprintf("%02d:%02d - %02d:%02d", $startHour, 0, $endHour, $endMinute) . '</td>';
                $deskripsi .= '<td style="border: 1px solid #dee2e6; padding: 12px;">' . $activity . '</td>';
                $deskripsi .= '</tr>';
                
                $startHour = $endHour + ($endMinute > 0 ? 1 : 0);
            }
            $deskripsi .= '</tbody>';
            $deskripsi .= '</table>';
        }

        // Section Yang Akan Dipelajari
        if ($faker->boolean(80)) {
            $deskripsi .= '<h3>Apa yang Akan Anda Pelajari?</h3>';
            $deskripsi .= '<p>Dalam event ini, Anda akan mendapatkan insight mendalam tentang:</p>';
            $deskripsi .= '<ol>';
            for ($i = 0; $i < rand(4, 6); $i++) {
                $deskripsi .= '<li><strong>' . $fakerEN->words(rand(3, 5), true) . '</strong><br>';
                $deskripsi .= $faker->sentence(rand(8, 15)) . '</li>';
            }
            $deskripsi .= '</ol>';
        }

        // Section Target Peserta
        if ($faker->boolean(75)) {
            $deskripsi .= '<h3>Siapa yang Harus Hadir?</h3>';
            $targets = [
                'Entrepreneur dan Startup Founder',
                'Marketing Professional',
                'Digital Marketing Specialist',
                'Business Development Manager',
                'Product Manager',
                'C-Level Executive',
                'Fresh Graduate',
                'Business Owner',
                'Consultant',
                'Project Manager'
            ];
            
            $selectedTargets = $faker->randomElements($targets, rand(3, 5));
            $deskripsi .= '<ul>';
            foreach ($selectedTargets as $target) {
                $deskripsi .= '<li>' . $target . '</li>';
            }
            $deskripsi .= '</ul>';
        }

        // Section Fasilitas dengan icons atau styling
        if ($faker->boolean(85)) {
            $deskripsi .= '<h3>Fasilitas yang Anda Dapatkan</h3>';
            $facilities = [
                '🏆 Sertifikat resmi yang diakui industri',
                '📚 Materi pembelajaran lengkap dalam bentuk digital',
                '☕ Coffee break dan makan siang',
                '🎁 Merchandise eksklusif',
                '🤝 Networking session dengan para professional',
                '📱 Akses grup komunitas eksklusif',
                '💼 Template dan tools yang siap pakai',
                '🎥 Recording session untuk review kembali'
            ];
            
            $selectedFacilities = $faker->randomElements($facilities, rand(4, 6));
            $deskripsi .= '<ul>';
            foreach ($selectedFacilities as $facility) {
                $deskripsi .= '<li>' . $facility . '</li>';
            }
            $deskripsi .= '</ul>';
        }

        // Section Harga dan Promo (jika ada)
        if ($faker->boolean(70)) {
            $deskripsi .= '<h3>Investasi & Promo Spesial</h3>';
            $originalPrice = rand(200, 800) * 1000; // Harga dalam ribuan
            $discountPrice = $originalPrice - (rand(20, 50) * 1000);
            
            $deskripsi .= '<div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0;">';
            $deskripsi .= '<h4 style="color: #856404; margin-top: 0;">🔥 Early Bird Special!</h4>';
            $deskripsi .= '<p style="color: #856404; margin-bottom: 10px;">';
            $deskripsi .= '<span style="text-decoration: line-through; color: #6c757d;">Rp ' . number_format($originalPrice, 0, ',', '.') . '</span> ';
            $deskripsi .= '<strong style="color: #d63384; font-size: 1.2em;">Rp ' . number_format($discountPrice, 0, ',', '.') . '</strong>';
            $deskripsi .= '</p>';
            $deskripsi .= '<p style="color: #856404; margin-bottom: 0;"><em>*Promo terbatas untuk 50 pendaftar pertama</em></p>';
            $deskripsi .= '</div>';
        }

        // Section Requirements atau Persiapan
        if ($faker->boolean(60)) {
            $deskripsi .= '<h3>Persiapan yang Dibutuhkan</h3>';
            if ($isOnline) {
                $deskripsi .= '<ul>';
                $deskripsi .= '<li>Koneksi internet yang stabil</li>';
                $deskripsi .= '<li>Laptop atau PC dengan kamera dan mikrofon</li>';
                $deskripsi .= '<li>Aplikasi Zoom/Teams (akan diberikan link sebelum acara)</li>';
                $deskripsi .= '<li>Notepad untuk mencatat poin-poin penting</li>';
                $deskripsi .= '</ul>';
            } else {
                $deskripsi .= '<ul>';
                $deskripsi .= '<li>Laptop atau notepad untuk mencatat</li>';
                $deskripsi .= '<li>Business card untuk networking</li>';
                $deskripsi .= '<li>Pakaian smart casual</li>';
                $deskripsi .= '<li>Semangat belajar yang tinggi!</li>';
                $deskripsi .= '</ul>';
            }
        }

        // Call to Action Section
        $deskripsi .= '<h3>Jangan Sampai Terlewat!</h3>';
        $deskripsi .= '<div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 20px; margin: 20px 0;">';
        $deskripsi .= '<p style="color: #0c5460; margin-bottom: 10px;"><strong>Kapasitas terbatas!</strong> Segera daftarkan diri Anda sebelum kuota penuh.</p>';
        $deskripsi .= '<p style="color: #0c5460; margin-bottom: 0;">Investasi terbaik adalah investasi untuk diri sendiri. Jangan biarkan kesempatan emas ini berlalu begitu saja!</p>';
        $deskripsi .= '</div>';

        // Penutup dengan contact info
        if ($faker->boolean(80)) {
            $deskripsi .= '<h3>Butuh Informasi Lebih Lanjut?</h3>';
            $deskripsi .= '<div style="background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0;">';
            $deskripsi .= '<p style="margin-bottom: 10px;"><strong>Hubungi kami:</strong></p>';
            $deskripsi .= '<ul style="margin-bottom: 0;">';
            $deskripsi .= '<li>📧 Email: info@example.com</li>';
            $deskripsi .= '<li>📱 WhatsApp: +62 812-3456-7890</li>';
            $deskripsi .= '<li>🌐 Website: www.example.com</li>';
            $deskripsi .= '</ul>';
            $deskripsi .= '</div>';
        }

        return $deskripsi;
    }
}