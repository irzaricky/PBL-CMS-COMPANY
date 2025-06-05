<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $testimonials = [];

        for ($i = 1; $i <= 50; $i++) {
            // Generate random testimonial text directly using Faker
            $isi_testimoni = $faker->paragraph(rand(2, 4));

            $createdAt = $faker->dateTimeBetween('-6 months', 'now');

            $testimonials[] = [
                'id_testimoni' => $i,
                'id_user' => $faker->numberBetween(9, 13),
                'isi_testimoni' => $isi_testimoni,
                'rating' => $faker->numberBetween(1, 5),
                'status' => $faker->randomElement([ContentStatus::TERPUBLIKASI->value, ContentStatus::TIDAK_TERPUBLIKASI->value]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('testimoni')->insert($testimonials);
    }
}