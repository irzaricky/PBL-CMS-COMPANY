<?php

namespace Database\Seeders;

use App\Models\Artikel;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        // Create 100 articles using the factory
        Artikel::factory()->count(100)->create();
    }
}