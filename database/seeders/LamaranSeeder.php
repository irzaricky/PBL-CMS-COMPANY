<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LamaranSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $statusOptions = ['Diproses', 'Diterima', 'Ditolak'];

        $lamaran = [
            [
                'id_lamaran' => 1,
                'id_user' => 9,
                'id_lowongan' => 1,
                'status_lamaran' => $faker->randomElement($statusOptions),
                'created_at' => $faker->dateTimeBetween('-3 months', '-2 months'),
                'updated_at' => $faker->dateTimeBetween('-2 months', '-1 month'),
            ],
            [
                'id_lamaran' => 2,
                'id_user' => 10,
                'id_lowongan' => 2,
                'status_lamaran' => $faker->randomElement($statusOptions),
                'created_at' => $faker->dateTimeBetween('-3 months', '-2 months'),
                'updated_at' => $faker->dateTimeBetween('-2 months', '-1 month'),
            ],
            [
                'id_lamaran' => 3,
                'id_user' => 11,
                'id_lowongan' => 3,
                'status_lamaran' => $faker->randomElement($statusOptions),
                'created_at' => $faker->dateTimeBetween('-3 months', '-2 months'),
                'updated_at' => $faker->dateTimeBetween('-2 months', '-1 month'),
            ],

            [
                'id_lamaran' => 4,
                'id_user' => 12,
                'id_lowongan' => 1,
                'status_lamaran' => $faker->randomElement($statusOptions),
                'created_at' => $faker->dateTimeBetween('-3 months', '-2 months'),
                'updated_at' => $faker->dateTimeBetween('-2 months', '-1 month'),
            ],
            [
                'id_lamaran' => 5,
                'id_user' => 13,
                'id_lowongan' => 2,
                'status_lamaran' => $faker->randomElement($statusOptions),
                'created_at' => $faker->dateTimeBetween('-2 months', '-1 month'),
                'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
            ],
        ];

        DB::table('lamaran')->insert($lamaran);
    }
}