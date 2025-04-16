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
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);

        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'tanggal_registrasi' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Create editor user
        $editorUser = User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'tanggal_registrasi' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Assign roles to users
        $adminUser->assignRole($adminRole);
        $editorUser->assignRole($editorRole);
    }
}