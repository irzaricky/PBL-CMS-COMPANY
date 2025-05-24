<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $files = Storage::disk('public')->allFiles();
        $directories = Storage::disk('public')->allDirectories();

        // Hapus semua file kecuali .gitignore
        foreach ($files as $file) {
            if (basename($file) !== '.gitignore') {
                Storage::disk('public')->delete($file);
            }
        }

        // Hapus semua folder mulai dari yang terdalam
        foreach (array_reverse($directories) as $directory) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        // Essential seeders - check if data already exists
        $this->runEssentialSeeders();

        $this->call([
            FilamentUserSeeder::class,
            DummyUser::class,
            KategoriUnduhanSeeder::class,
            KategoriProdukSeeder::class,
            KategoriGaleriSeeder::class,
            KategoriArtikelSeeder::class,


            UnduhanSeeder::class,
            ProdukSeeder::class,
            GaleriSeeder::class,


            MediaSosialSeeder::class,
            FeedbackSeeder::class,
            TestimoniSeeder::class,
            LowonganSeeder::class,
            EventSeeder::class,
            ArtikelSeeder::class,


            LamaranSeeder::class,
            MitraSeeder::class,
            StrukturOrganisasiSeeder::class,
            CaseStudySeeder::class,
            TestimoniProdukSeeder::class,
        ]);
    }

    /**
     * Run essential seeders with existence checks
     */
    private function runEssentialSeeders(): void
    {
        // Check and run ShieldSeeder if no roles exist
        if (
            class_exists('Spatie\Permission\Models\Role') &&
            \Spatie\Permission\Models\Role::count() === 0
        ) {
            $this->call(ShieldSeeder::class);
        }

        // Check and run ProfilPerusahaanSeeder if no company profile exists
        if (\App\Models\ProfilPerusahaan::count() === 0) {
            $this->call(ProfilPerusahaanSeeder::class);
        }

        // Check and run KontenSliderSeeder if no slider content exists
        if (\App\Models\KontenSlider::count() === 0) {
            $this->call(KontenSliderSeeder::class);
        }

        // Check and run FeatureToggleSeeder if no feature toggles exist
        if (\App\Models\FeatureToggle::count() === 0) {
            $this->call(FeatureToggleSeeder::class);
        }
    }
}