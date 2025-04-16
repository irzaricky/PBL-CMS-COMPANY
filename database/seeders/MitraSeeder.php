<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mitra')->insert([
            [
                'id_mitra' => 1,
                'nama' => 'PT Global Tech Solutions',
                'alamat_mitra' => 'Jl. Raya Kebon Jeruk No. 123, Jakarta Barat',
                'dok_siup' => 'dokumen/siup-gts.pdf',
                'dok_npwp' => 'dokumen/npwp-gts.pdf',
                'tanggal_kemitraan' => '2023-01-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mitra' => 2,
                'nama' => 'CV Digital Kreatif',
                'alamat_mitra' => 'Jl. Mangga Dua Raya No. 45, Jakarta Utara',
                'dok_siup' => 'dokumen/siup-dk.pdf',
                'dok_npwp' => 'dokumen/npwp-dk.pdf',
                'tanggal_kemitraan' => '2023-05-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mitra' => 3,
                'nama' => 'PT Solusi Andal Indonesia',
                'alamat_mitra' => 'Jl. Sudirman No. 78, Jakarta Pusat',
                'dok_siup' => 'dokumen/siup-sai.pdf',
                'dok_npwp' => 'dokumen/npwp-sai.pdf',
                'tanggal_kemitraan' => '2024-02-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}