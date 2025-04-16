<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('struktur_organisasi')->insert([
            [
                'id_struktur_organisasi' => 1,
                'id_user' => 1,
                'deskripsi' => 'Direktur Utama',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_struktur_organisasi' => 2,
                'id_user' => 2,
                'deskripsi' => 'Manajer Teknologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}