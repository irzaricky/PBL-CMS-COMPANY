<?php

namespace Database\Seeders;

use App\Models\Testimoni;
use Illuminate\Database\Seeder;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        // Create 15 testimonials using the factory
        Testimoni::factory()->count(15)->create();
    }
}