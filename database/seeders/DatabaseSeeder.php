<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FilamentUserSeeder::class,
            KategoriUnduhanSeeder::class,
            KategoriProdukSeeder::class,
            KategoriGaleriSeeder::class,
            KategoriArtikelSeeder::class,

                // These are independent
            UnduhanSeeder::class,
            ProdukSeeder::class,
            GaleriSeeder::class,


            ProfilPerusahaanSeeder::class,
            MediaSosialSeeder::class,
            FeedbackSeeder::class,
            TestimoniSeeder::class,
            LowonganSeeder::class,
            EventSeeder::class,
            ArtikelSeeder::class,

                // These depend on the above
            LamaranSeeder::class,
            KontenSliderSeeder::class,
            MitraSeeder::class,
            StrukturOrganisasiSeeder::class,
        ]);
    }
}