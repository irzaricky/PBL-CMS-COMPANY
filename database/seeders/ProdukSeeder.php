<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Base products data
        $products = [
            ['nama' => 'Aplikasi Manajemen Keuangan', 'kategori' => 3, 'harga' => 1500000],
            ['nama' => 'Jasa Konsultasi IT', 'kategori' => 2, 'harga' => 500000],
            ['nama' => 'Server Rack', 'kategori' => 1, 'harga' => 15000000],
            ['nama' => 'Sistem Manajemen Inventaris', 'kategori' => 3, 'harga' => 2750000],
            ['nama' => 'Aplikasi HR & Kepegawaian', 'kategori' => 3, 'harga' => 3200000],
            ['nama' => 'Layanan Pengembangan Website', 'kategori' => 2, 'harga' => 5000000],
            ['nama' => 'Maintenance & Support IT', 'kategori' => 2, 'harga' => 2000000],
            ['nama' => 'Networking Kit Enterprise', 'kategori' => 1, 'harga' => 8500000],
            ['nama' => 'Workstation PC Pro', 'kategori' => 1, 'harga' => 12750000],
            ['nama' => 'Sistem Keamanan Data Enterprise', 'kategori' => 3, 'harga' => 4500000],
        ];

        // Generate 50 products
        for ($i = 1; $i <= 50; $i++) {
            $randomProduct = $faker->randomElement($products);
            $createdAt = Carbon::now()->subYear()->addDays(rand(0, 365));
            
            DB::table('produk')->insert([
                'id_produk' => $i,
                'id_kategori_produk' => $randomProduct['kategori'],
                'nama_produk' => $randomProduct['nama'] . ' ' . $faker->words(2, true),
                'harga_produk' => 'Rp ' . number_format($randomProduct['harga'] * $faker->randomFloat(1, 0.8, 1.2), 0, ',', '.'),
                'slug' => Str::slug($randomProduct['nama'] . ' ' . $faker->words(2, true)),
                'deskripsi_produk' => $faker->paragraph(2),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->addDays(rand(0, 30)),
            ]);
        }
    }
}