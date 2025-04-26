<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class FilamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        // $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $contentManagementRole = Role::firstOrCreate(['name' => 'Content Management']);
        $customerServiceRole = Role::firstOrCreate(['name' => 'Customer Service']);

        // Create admin user
        $adminUser = User::create([
            'name' => 'John Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'tanggal_registrasi' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Create Content Manager user
        $contentManagementUser = User::create([
            'name' => 'John Editor',
            'email' => 'editor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'tanggal_registrasi' => now(),
            'remember_token' => Str::random(10),
        ]);



        // Create Customer Service user
        $customerServiceUser = User::create([
            'name' => 'John Customer Service',
            'email' => 'CS@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'tanggal_registrasi' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Assign roles to users
        $adminUser->assignRole('super_admin');
        $contentManagementUser->assignRole($contentManagementRole);
        $customerServiceUser->assignRole($customerServiceRole);
    }
}