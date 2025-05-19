<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MediaSosialSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $companyName = 'biiscorp';

        $mediaSosial = [
            [
                'id_media_sosial' => 1,
                'nama_media_sosial' => 'Facebook',
                'link' => 'https://facebook.com/' . $companyName,
                'status' => ContentStatus::TERPUBLIKASI->value,
                'created_at' => $faker->dateTimeBetween('-1 year', '-6 months'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ],
            [
                'id_media_sosial' => 2,
                'nama_media_sosial' => 'Instagram',
                'link' => 'https://instagram.com/' . $companyName,
                'status' => ContentStatus::TERPUBLIKASI->value,
                'created_at' => $faker->dateTimeBetween('-1 year', '-6 months'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ],
            [
                'id_media_sosial' => 3,
                'nama_media_sosial' => 'LinkedIn',
                'link' => 'https://linkedin.com/company/' . $companyName,
                'status' => ContentStatus::TERPUBLIKASI->value,
                'created_at' => $faker->dateTimeBetween('-1 year', '-6 months'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ],
            [
                'id_media_sosial' => 4,
                'nama_media_sosial' => 'Twitter',
                'link' => 'https://twitter.com/' . $companyName,
                'status' => ContentStatus::TIDAK_TERPUBLIKASI->value,
                'created_at' => $faker->dateTimeBetween('-1 year', '-6 months'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ],
            [
                'id_media_sosial' => 5,
                'nama_media_sosial' => 'YouTube',
                'link' => 'https://youtube.com/c/' . $companyName,
                'status' => ContentStatus::TIDAK_TERPUBLIKASI->value,
                'created_at' => $faker->dateTimeBetween('-1 year', '-6 months'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ],
            [
                'id_media_sosial' => 6,
                'nama_media_sosial' => 'TikTok',
                'link' => 'https://tiktok.com/@' . $companyName,
                'status' => ContentStatus::TIDAK_TERPUBLIKASI->value,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ],
        ];

        DB::table('media_sosial')->insert($mediaSosial);
    }
}