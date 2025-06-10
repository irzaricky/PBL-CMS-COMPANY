<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('testimoni')->insert([
            [
                'id_testimoni' => null,
                'id_testimoni_produk' => null,
                'id_testimoni_lowongan' => null,
                'id_testimoni_artikel' => null,
                'id_testimoni_artikel' => null,
            ],
        ]);
    }
}
