<?php

namespace Database\Seeders;

use Illuminate\Http\File;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilPerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $companyName = 'Biiscorp';

        $sourcePath = database_path('seeders/seeder_logo_perusahaan/');
        $targetPath = 'logo-perusahaan';
        $originalFile = 'Logo.png';
        Storage::disk('public')->makeDirectory($targetPath);
        Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $originalFile);

        $sejarahPerusahaan = sprintf(
            'Didirikan pada tahun %d, PT %s telah berkembang pesat dalam menyediakan solusi teknologi yang inovatif dan berkualitas tinggi. ' .
                'Perusahaan ini diawali oleh sekelompok %d teknisi dan pengembang software visioner yang memiliki misi untuk %s. ' .
                'Dalam perkembangannya, perusahaan telah melayani lebih dari %d klien dari berbagai sektor industri dan ' .
                'telah menyelesaikan lebih dari %d proyek besar. %s telah menjadi salah satu pemain utama di industri teknologi ' .
                'dengan spesialisasi dalam %s, %s, dan %s.',
            $faker->numberBetween(2015, 2022),
            $companyName,
            $faker->numberBetween(3, 10),
            $faker->sentence(15),
            $faker->numberBetween(50, 500),
            $faker->numberBetween(20, 200),
            $companyName,
            $faker->words(3, true),
            $faker->words(3, true),
            $faker->words(3, true)
        );

        $visiPerusahaan = sprintf(
            'Menjadi perusahaan teknologi terkemuka yang memberikan solusi inovatif untuk meningkatkan efisiensi dan produktivitas bisnis. ' .
                'Kami bercita-cita untuk %s dan menjadi pemimpin pasar dalam %s pada tahun %d.',
            $faker->sentence(10),
            $faker->words(5, true),
            $faker->numberBetween(2025, 2030)
        );

        $misiPerusahaan = sprintf(
            'Menyediakan solusi teknologi yang berkualitas tinggi, memberikan layanan terbaik kepada pelanggan, ' .
                'dan berkontribusi pada perkembangan industri teknologi di Indonesia. ' .
                'Kami berkomitmen untuk:\n' .
                '1. %s\n' .
                '2. %s\n' .
                '3. %s\n' .
                '4. %s\n' .
                '5. %s',
            $faker->sentence(8),
            $faker->sentence(8),
            $faker->sentence(8),
            $faker->sentence(8),
            $faker->sentence(8)
        );

        DB::table('profil_perusahaan')->insert([
            [
                'id_profil_perusahaan' => 1,
                'nama_perusahaan' => $companyName,
                'deskripsi_perusahaan' => sprintf(
                    'PT %s adalah perusahaan teknologi yang bergerak di bidang pengembangan software, konsultasi IT, ' .
                        'dan penyediaan solusi teknologi untuk berbagai sektor industri. %s',
                    $companyName,
                    $faker->paragraph(3)
                ),
                'logo_perusahaan' => $targetPath .'/'. $originalFile,
                'alamat_perusahaan' => $faker->address,
                'link_alamat_perusahaan' => 'https://maps.app.goo.gl/EAs3t7yqP9LotHiK6',
                'email_perusahaan' => strtolower($companyName) . '@' . $faker->freeEmailDomain,
                'sejarah_perusahaan' => $sejarahPerusahaan,
                'visi_perusahaan' => $visiPerusahaan,
                'misi_perusahaan' => $misiPerusahaan,
                'tema_perusahaan' => '#31487A',
            ],
        ]);
    }
}
