<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilPerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profil_perusahaan')->insert([
            [
                'id_profil_perusahaan' => 1,
                'nama_perusahaan' => 'Biiscorp',
                'deskripsi_perusahaan' => 'PT Biiscorp adalah perusahaan teknologi yang bergerak di bidang pengembangan software, konsultasi IT, dan penyediaan solusi teknologi untuk berbagai sektor industri.',
                'alamat_perusahaan' => 'Jl. Teknologi No. 123, Jakarta Selatan',
                'link_alamat_perusahaan' => 'https://maps.app.goo.gl/EAs3t7yqP9LotHiK6',
                'email_perusahaan' => 'Biiscorp@gmail.com',
                'sejarah_perusahaan' => 'Didirikan pada tahun 2020, PT Biiscorp telah berkembang pesat dalam menyediakan solusi teknologi yang inovatif dan berkualitas tinggi.',
                'visi_perusahaan' => 'Menjadi perusahaan teknologi terkemuka yang memberikan solusi inovatif untuk meningkatkan efisiensi dan produktivitas bisnis.',
                'misi_perusahaan' => 'Menyediakan solusi teknologi yang berkualitas tinggi, memberikan layanan terbaik kepada pelanggan, dan berkontribusi pada perkembangan industri teknologi di Indonesia.',
            ],
        ]);
    }
}