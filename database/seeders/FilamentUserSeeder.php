<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FilamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'John Admin',
            'status_kepegawaian' => 'Tetap',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
        ]);

        // Create Director user
        $directorUser = User::create([
            'name' => 'John Director',
            'status_kepegawaian' => 'Tetap',
            'email' => 'director@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
        ]);

        // Create Content Manager users with unique emails
        $contentManagers = [
            [
                'name' => 'John Editor',
                'email' => 'editor1@example.com',
            ],
            [
                'name' => 'Johny Editor',
                'email' => 'editor2@example.com',
            ],
            [
                'name' => 'Johnes Editor',
                'email' => 'editor3@example.com',
            ],
        ];

        $contentManagerUsers = [];
        foreach ($contentManagers as $manager) {
            $user = User::create([
                'name' => $manager['name'],
                'status_kepegawaian' => 'Tetap',
                'email' => $manager['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
            ]);
            $user->assignRole('Content Management');
            $contentManagerUsers[] = $user;
        }


        // Create Customer Service users with unique emails
        $customerServices = [
            [
                'name' => 'John Customer Service',
                'email' => 'cs1@example.com',
            ],
            [
                'name' => 'Johny Customer Service',
                'email' => 'cs2@example.com',
            ],
            [
                'name' => 'Johnes Customer Service',
                'email' => 'cs3@example.com',
            ],
        ];

        $customerServiceUsers = [];
        foreach ($customerServices as $cs) {
            $user = User::create([
                'name' => $cs['name'],
                'status_kepegawaian' => 'Tetap',
                'email' => $cs['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
            ]);
            $user->assignRole('Customer Service');
            $customerServiceUsers[] = $user;
        }

        // Assign roles to admin and director
        $adminUser->assignRole('super_admin');
        $directorUser->assignRole('Director');
    }
}