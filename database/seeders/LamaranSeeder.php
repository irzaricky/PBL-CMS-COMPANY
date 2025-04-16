<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LamaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lamaran')->insert([
            [
                'id_lamaran' => 1,
                'id_user' => 2,
                'id_lowongan' => 1,
                'status_lamaran' => 'Diproses',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lamaran' => 2,
                'id_user' => 2,
                'id_lowongan' => 2,
                'status_lamaran' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lamaran' => 3,
                'id_user' => 2,
                'id_lowongan' => 3,
                'status_lamaran' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}