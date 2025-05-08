<?php

namespace Database\Seeders;

use App\Models\Lamaran;
use Illuminate\Database\Seeder;

class LamaranSeeder extends Seeder
{
    public function run(): void
    {
        // Create applications with different statuses
        Lamaran::factory()->count(10)->processed()->create();
        Lamaran::factory()->count(10)->accepted()->create();
        Lamaran::factory()->count(10)->rejected()->create();
    }
}