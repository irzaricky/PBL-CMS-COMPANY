<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class FilamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Siapkan gambar dari folder seeder
        $sourcePath = database_path('seeders/seeder_image_foto_profil');
        $targetPath = 'profile-photos';
        Storage::disk('public')->makeDirectory($targetPath);

        $files = array_values(array_filter(scandir($sourcePath), fn($f) => !in_array($f, ['.', '..'])));

        // Fungsi ambil dan simpan 1 gambar acak
        $getRandomProfilePicture = function () use ($files, $sourcePath, $targetPath) {
            $originalFile = $files[array_rand($files)];
            $newFileName = Str::random(12) . '-' . $originalFile;
            Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $newFileName);
            return $targetPath . '/' . $newFileName;
        };

        // Admin user
        $adminUser = User::create([
            'name' => 'John Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $adminUser->assignRole('super_admin');

        // Director user
        $directorUser = User::create([
            'name' => 'John Director',
            'email' => 'director@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $directorUser->assignRole('Director');

        // Content Management users
        $editor1 = User::create([
            'name' => 'John Editor',
            'email' => 'editor1@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $editor2 = User::create([
            'name' => 'Johny Editor',
            'email' => 'editor2@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $editor3 = User::create([
            'name' => 'Johnes Editor',
            'email' => 'editor3@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'status' => 'nonaktif',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
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
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $cs2 = User::create([
            'name' => 'Johny Customer Service',
            'email' => 'cs2@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'status' => 'nonaktif',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $cs3 = User::create([
            'name' => 'Johnes Customer Service',
            'email' => 'cs3@example.com',
            'password' => bcrypt('password123'),
            'status_kepegawaian' => 'Tetap',
            'email_verified_at' => now(),
            'foto_profil' => $getRandomProfilePicture(),
        ]);
        $cs1->assignRole('Customer Service');
        $cs2->assignRole('Customer Service');
        $cs3->assignRole('Customer Service');
    }
}
