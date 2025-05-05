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
        $lamaran = [];

        // Generate 30 applications
        for ($i = 1; $i <= 30; $i++) {
            $createdAt = $faker->dateTimeBetween('-3 months', '-1 month');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            $lamaran[] = [
                'id_lamaran' => $i,
                'id_user' => $faker->numberBetween(9, 13),
                'id_lowongan' => $faker->numberBetween(1, 3),
                'nama_asli' => $faker->name,
                'status_lamaran' => $faker->randomElement($statusOptions),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        DB::table('lamaran')->insert($lamaran);
    }
}