<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class FilamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a super admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
        ]);

        // Check if using spatie/laravel-permission
        if (class_exists(Role::class)) {
            // Create roles if they don't exist
            $adminRole = Role::firstOrCreate(['name' => 'super_admin']);

            // Assign role to user
            $user->assignRole($adminRole);
        }

        // Create additional users if needed
        User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
        ]);
    }
}