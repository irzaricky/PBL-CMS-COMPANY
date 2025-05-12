<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Laravel\Prompts;

// Seeder Imports
use Database\Seeders\DummyUser;
use Database\Seeders\EventSeeder;
use Database\Seeders\MitraSeeder;
use Database\Seeders\GaleriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\ShieldSeeder;
use Database\Seeders\ArtikelSeeder;
use Database\Seeders\LamaranSeeder;
use Database\Seeders\UnduhanSeeder;
use Database\Seeders\FeedbackSeeder;
use Database\Seeders\LowonganSeeder;
use Database\Seeders\CaseStudySeeder;
use Database\Seeders\TestimoniSeeder;
use Database\Seeders\MediaSosialSeeder;
use Database\Seeders\FilamentUserSeeder;
use Database\Seeders\KontenSliderSeeder;
use Database\Seeders\FeatureToggleSeeder;
use Database\Seeders\KategoriGaleriSeeder;
use Database\Seeders\KategoriProdukSeeder;
use Database\Seeders\KategoriArtikelSeeder;
use Database\Seeders\KategoriUnduhanSeeder;
use Database\Seeders\ProfilPerusahaanSeeder;
use Database\Seeders\StrukturOrganisasiSeeder;

class SeedWithPrompt extends Command
{
    protected $signature = 'db:seed:prompt {--force : Jalankan seeder tanpa konfirmasi}';
    protected $description = 'Jalankan seeder dengan pilihan interaktif menggunakan panah + spasi';

    public function handle()
    {
        $this->info('isTTY: ' . (function_exists('posix_isatty') ? (posix_isatty(STDOUT) ? 'yes' : 'no') : 'not supported'));

        // Kumpulan seeder dikelompokkan berdasarkan kategori
        $seederGroups = [
            'Users' => [
                ShieldSeeder::class,
                FilamentUserSeeder::class,
                DummyUser::class,
            ],
            'Kategori' => [
                KategoriUnduhanSeeder::class,
                KategoriProdukSeeder::class,
                KategoriGaleriSeeder::class,
                KategoriArtikelSeeder::class,
            ],
            'Konten' => [
                UnduhanSeeder::class,
                ProdukSeeder::class,
                GaleriSeeder::class,
                ArtikelSeeder::class,
                KontenSliderSeeder::class,
                CaseStudySeeder::class,
            ],
            'Perusahaan' => [
                ProfilPerusahaanSeeder::class,
                MediaSosialSeeder::class,
                StrukturOrganisasiSeeder::class,
                MitraSeeder::class,
            ],
            'Interaksi' => [
                FeedbackSeeder::class,
                TestimoniSeeder::class,
                LowonganSeeder::class,
                LamaranSeeder::class,
                EventSeeder::class,
            ],
            'System' => [
                FeatureToggleSeeder::class,
            ],
        ];

        // Flatten dan buat pilihan user-friendly
        $choices = [
            '🔥 Semua Seeder' => 'all',
        ];

        foreach ($seederGroups as $group => $seeders) {
            $choices["🗂️ Grup: {$group}"] = $group;
            foreach ($seeders as $seeder) {
                $choices["   ↳ " . class_basename($seeder)] = $seeder;
            }
        }

        // Prompt user untuk memilih seeder
        $selected = Prompts\multiselect(
            label: 'Pilih seeder yang ingin dijalankan:',
            options: $choices,
            hint: 'Gunakan panah + spasi, lalu tekan Enter.'
        );

        if (empty($selected)) {
            $this->info('Tidak ada seeder yang dipilih.');
            return;
        }

        $allSeeders = collect($seederGroups)->flatMap(fn($s) => $s)->toArray();
        $seederToRun = [];

        foreach ($selected as $choice) {
            if ($choice === 'all') {
                $seederToRun = $allSeeders;
                break;
            } elseif (isset($seederGroups[$choice])) {
                $seederToRun = array_merge($seederToRun, $seederGroups[$choice]);
            } elseif (class_exists($choice)) {
                $seederToRun[] = $choice;
            }
        }

        $seederToRun = array_unique($seederToRun);

        if (empty($seederToRun)) {
            $this->warn('Tidak ada seeder valid yang akan dijalankan.');
            return;
        }

        // Tampilkan ringkasan dan minta konfirmasi
        $this->info('Seeder yang akan dijalankan:');
        foreach ($seederToRun as $seeder) {
            $this->line('- ' . class_basename($seeder));
        }

        if (!$this->option('force') && !Prompts\confirm('Lanjutkan menjalankan seeder?', true)) {
            $this->info('Operasi dibatalkan.');
            return;
        }

        // Jalankan seeder satu per satu
        $totalStart = microtime(true);
        $errors = [];

        foreach ($seederToRun as $seeder) {
            $seederName = class_basename($seeder);
            $this->line("\nMenjalankan: <info>{$seederName}</info>");
            $start = microtime(true);

            try {
                Artisan::call('db:seed', [
                    '--class' => $seeder,
                    '--force' => $this->option('force'),
                ]);
                $this->output->writeln(Artisan::output());
                $this->info("✓ {$seederName} selesai dalam " . round(microtime(true) - $start, 2) . " detik");
            } catch (\Exception $e) {
                $errors[] = "{$seederName}: " . $e->getMessage();
                $this->error("✘ {$seederName} gagal: " . $e->getMessage());
            }
        }

        $totalDuration = round(microtime(true) - $totalStart, 2);

        if (empty($errors)) {
            $this->info("Semua seeder berhasil dijalankan dalam {$totalDuration} detik.");
        } else {
            $this->warn("Selesai dengan error. Durasi total: {$totalDuration} detik.");
            $this->line("Ringkasan error:");
            foreach ($errors as $error) {
                $this->error("- {$error}");
            }
        }
    }
}
