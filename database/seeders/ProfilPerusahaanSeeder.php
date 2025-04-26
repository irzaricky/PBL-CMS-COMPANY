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
                'id_galeri' => 1,
                'nama_perusahaan' => 'Biiscorp',
                'deskripsi_perusahaan' => 'PT Biiscorp adalah perusahaan teknologi yang bergerak di bidang pengembangan software, konsultasi IT, dan penyediaan solusi teknologi untuk berbagai sektor industri.',
                'alamat_perusahaan' => 'Jl. Teknologi No. 123, Jakarta Selatan',
                'email_perusahaan' => 'Biiscorp@gmail.com',
            ],
        ]);
    }
}