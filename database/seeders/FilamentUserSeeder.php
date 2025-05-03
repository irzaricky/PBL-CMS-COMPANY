<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FilamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $adminUser = User::create([
            'name' => 'John Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('super_admin');


        // Director user
        $directorUser = User::create([
            'name' => 'John Director',
            'email' => 'director@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
        ]);
        $directorUser->assignRole('Director');


        // Content Management users
        $editor1 = User::create([
            'name' => 'John Editor',
            'email' => 'editor1@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
        ]);

        $editor2 = User::create([
            'name' => 'Johny Editor',
            'email' => 'editor2@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
        ]);

        $editor3 = User::create([
            'name' => 'Johnes Editor',
            'email' => 'editor3@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'status' => 'nonaktif',
        ]);

        $editor1->assignRole('Content Management');
        $editor2->assignRole('Content Management');
        $editor3->assignRole('Content Management');


        // Customer Service users
        $cs1 = User::create([
            'name' => 'John Customer Service',
            'email' => 'cs1@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
        ]);

        $cs2 = User::create([
            'name' => 'Johny Customer Service',
            'email' => 'cs2@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'status' => 'nonaktif',
        ]);

        $cs3 = User::create([
            'name' => 'Johnes Customer Service',
            'email' => 'cs3@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
        ]);
        $cs1->assignRole('Customer Service');
        $cs2->assignRole('Customer Service');
        $cs3->assignRole('Customer Service');
    }
}