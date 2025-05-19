<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Http\File;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // bagian proses image
        $sourcePath = database_path('seeders/seeder_image/');
        $targetPath = 'produk-thumbnails';

        // Pastikan folder target ada
        Storage::disk('public')->makeDirectory($targetPath);

        // Ambil semua file di folder seeder_image
        $files = array_values(array_filter(scandir($sourcePath), fn($f) => !in_array($f, ['.', '..'])));

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

        // Generate 20 products
        for ($i = 1; $i <= 20; $i++) {
            $randomProduct = $faker->randomElement($products);
            $createdAt = Carbon::now()->subYear()->addDays(rand(0, 365));

            // Generate array untuk menyimpan multiple images
            $images = [];

            // Tentukan jumlah gambar untuk produk ini (1-3 gambar)
            $imageCount = rand(1, 3);

            // Pilih dan proses beberapa gambar
            for ($j = 0; $j < $imageCount; $j++) {
                // Pilih gambar random
                $originalFile = $files[array_rand($files)];

                // Buat nama baru biar unik
                $newFileName = Str::random(10) . '-' . $originalFile;

                // Copy ke storage
                Storage::disk('public')->putFileAs($targetPath, new File("$sourcePath/$originalFile"), $newFileName);

                // Tambahkan path gambar ke array images
                $images[] = $targetPath . '/' . $newFileName;
            }

            DB::table('produk')->insert([
                'id_produk' => $i,
                'id_kategori_produk' => $randomProduct['kategori'],
                'nama_produk' => $randomProduct['nama'] . ' ' . $faker->words(2, true),
                'thumbnail_produk' => json_encode($images),
                'harga_produk' => 'Rp ' . number_format($randomProduct['harga'] * $faker->randomFloat(1, 0.8, 1.2), 0, ',', '.'),
                'slug' => Str::slug($randomProduct['nama'] . ' ' . $faker->words(2, true)),
                'status_produk' => $faker->randomElement(['terpublikasi', 'tidak terpublikasi']),
                'deskripsi_produk' => $faker->paragraph(10),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->addDays(rand(0, 30)),
            ]);
        }
    }
}