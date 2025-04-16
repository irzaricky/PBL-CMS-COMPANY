<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artikel')->insert([
            [
                'id_artikel' => 1,
                'id_kategori_artikel' => 1,
                'id_user' => 1,
                'judul_artikel' => 'Penerapan AI dalam Bisnis Modern',
                'konten_artikel' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p><p>Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p>',
                'slug' => 'penerapan-ai-dalam-bisnis-modern',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_artikel' => 2,
                'id_kategori_artikel' => 2,
                'id_user' => 2,
                'judul_artikel' => 'Strategi Marketing Digital 2025',
                'konten_artikel' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p><p>Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p>',
                'slug' => 'strategi-marketing-digital-2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_artikel' => 3,
                'id_kategori_artikel' => 3,
                'id_user' => 1,
                'judul_artikel' => 'Tutorial Laravel untuk Pemula',
                'konten_artikel' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p><p>Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p>',
                'slug' => 'tutorial-laravel-untuk-pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}