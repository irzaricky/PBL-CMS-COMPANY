<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'status_kepegawaian' => $faker->randomElement(['Kontrak', 'Magang', 'Non Pegawai']),
                'email_verified_at' => now(),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
