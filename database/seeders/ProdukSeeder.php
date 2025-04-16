<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produk')->insert([
            [
                'id_produk' => 1,
                'id_kategori_produk' => 3,
                'nama_produk' => 'Aplikasi Manajemen Keuangan',
                'harga_produk' => 'Rp 1.500.000',
                'slug' => 'aplikasi-manajemen-keuangan',
                'deskripsi_produk' => 'Aplikasi untuk mengelola keuangan perusahaan dengan fitur laporan keuangan, arus kas, dan analisis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 2,
                'id_kategori_produk' => 2,
                'nama_produk' => 'Jasa Konsultasi IT',
                'harga_produk' => 'Rp 500.000/jam',
                'slug' => 'jasa-konsultasi-it',
                'deskripsi_produk' => 'Layanan konsultasi IT untuk membantu perusahaan dalam menentukan solusi teknologi yang tepat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 3,
                'id_kategori_produk' => 1,
                'nama_produk' => 'Server Rack',
                'harga_produk' => 'Rp 15.000.000',
                'slug' => 'server-rack',
                'deskripsi_produk' => 'Server rack berkualitas tinggi untuk kebutuhan perusahaan dengan garansi 3 tahun.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}