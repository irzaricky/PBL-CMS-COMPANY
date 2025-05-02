<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('struktur_organisasi')->insert([
            // Management Level
            [
                'id_struktur_organisasi' => 1,
                'id_user' => 2, // John Director
                'jabatan' => 'Direktur Utama',
                'deskripsi' => 'Bertanggung jawab atas pengelolaan perusahaan secara keseluruhan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 2,
                'id_user' => 1, // John Admin
                'jabatan' => 'Chief Technology Officer',
                'deskripsi' => 'Bertanggung jawab atas pengembangan dan implementasi teknologi perusahaan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Content Management Team
            [
                'id_struktur_organisasi' => 3,
                'id_user' => 3, // John Editor
                'jabatan' => 'Content Manager',
                'deskripsi' => 'Bertanggung jawab atas pengelolaan konten dan strategi pemasaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 4,
                'id_user' => 4, // Johny Editor
                'jabatan' => 'Senior Content Writer',
                'deskripsi' => 'Bertanggung jawab atas penulisan dan pengeditan konten.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 5,
                'id_user' => 5, // Johnes Editor
                'jabatan' => 'Content Designer',
                'deskripsi' => 'Bertanggung jawab atas desain grafis dan visual konten.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Customer Service Team
            [
                'id_struktur_organisasi' => 6,
                'id_user' => 6, // John Customer Service
                'jabatan' => 'Customer Service Manager',
                'deskripsi' => 'Bertanggung jawab atas pengelolaan tim customer service dan kepuasan pelanggan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 7,
                'id_user' => 7, // Johny Customer Service
                'jabatan' => 'Customer Support Specialist',
                'deskripsi' => 'Bertanggung jawab atas dukungan pelanggan dan penyelesaian masalah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 8,
                'id_user' => 8, // Johnes Customer Service
                'jabatan' => 'Technical Support Representative',
                'deskripsi' => 'Bertanggung jawab atas dukungan teknis dan pemecahan masalah produk.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}