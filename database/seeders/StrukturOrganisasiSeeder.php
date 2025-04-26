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
                'deskripsi' => 'Direktur Utama',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 2,
                'id_user' => 1, // John Admin
                'deskripsi' => 'Chief Technology Officer',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Content Management Team
            [
                'id_struktur_organisasi' => 3,
                'id_user' => 3, // John Editor
                'deskripsi' => 'Content Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 4,
                'id_user' => 4, // Johny Editor
                'deskripsi' => 'Senior Content Writer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 5,
                'id_user' => 5, // Johnes Editor
                'deskripsi' => 'Content Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Customer Service Team
            [
                'id_struktur_organisasi' => 6,
                'id_user' => 6, // John Customer Service
                'deskripsi' => 'Customer Service Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 7,
                'id_user' => 7, // Johny Customer Service
                'deskripsi' => 'Customer Support Specialist',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 8,
                'id_user' => 8, // Johnes Customer Service
                'deskripsi' => 'Technical Support Representative',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}