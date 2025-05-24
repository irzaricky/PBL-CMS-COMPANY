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

        // Check for existing test user
        $testEmail = 'testuser@example.com';
        $testUser = User::where('email', $testEmail)->first();
        if (!$testUser) {
            User::create([
                'name' => 'Test User',
                'email' => $testEmail,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);
        }

        $faker = \Faker\Factory::create();

        // Loop to create random users
        for ($i = 0; $i < 20; $i++) {
            // Generate unique email
            $email = $faker->unique()->safeEmail();

            // Skip if this email already exists in the database
            if (User::where('email', $email)->exists()) {
                continue;
            }

            User::create([
                'name' => $faker->name(),
                'email' => $email,
                'password' => Hash::make('password123'),
                'status_kepegawaian' => $faker->randomElement(['Kontrak', 'Magang']),
                'email_verified_at' => now(),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
