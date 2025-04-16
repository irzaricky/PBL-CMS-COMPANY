<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaSosialSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('media_sosial')->insert([
            [
                'id_media_sosial' => 1,
                'nama_media_sosial' => 'Facebook',
                'link' => 'https://facebook.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_media_sosial' => 2,
                'nama_media_sosial' => 'Instagram',
                'link' => 'https://instagram.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_media_sosial' => 3,
                'nama_media_sosial' => 'LinkedIn',
                'link' => 'https://linkedin.com/company/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}