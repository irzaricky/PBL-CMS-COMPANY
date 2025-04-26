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
            ShieldSeeder::class,
            FilamentUserSeeder::class,
            DummyUser::class,
            KategoriUnduhanSeeder::class,
            KategoriProdukSeeder::class,
            KategoriGaleriSeeder::class,
            KategoriArtikelSeeder::class,


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


            LamaranSeeder::class,
            KontenSliderSeeder::class,
            MitraSeeder::class,
            StrukturOrganisasiSeeder::class,
        ]);
    }
}