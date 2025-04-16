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
                'icon' => 'icon/facebook.png',
                'link' => 'https://facebook.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_media_sosial' => 2,
                'nama_media_sosial' => 'Instagram',
                'icon' => 'icon/instagram.png',
                'link' => 'https://instagram.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_media_sosial' => 3,
                'nama_media_sosial' => 'LinkedIn',
                'icon' => 'icon/linkedin.png',
                'link' => 'https://linkedin.com/company/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}