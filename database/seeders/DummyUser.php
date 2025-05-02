<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DummyUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Test User 1',
                'email' => 'testuser1@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test User 2',
                'email' => 'testuser2@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test User 3',
                'email' => 'testuser3@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test User 4',
                'email' => 'testuser4@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test User 5',
                'email' => 'testuser5@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($userData as $user) {
            User::create([
                ...$user
            ]);
        }
    }
}