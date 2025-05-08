<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 feedback records using the factory
        Feedback::factory()->count(20)->create();
    }
}