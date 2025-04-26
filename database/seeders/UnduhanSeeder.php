<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnduhanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('unduhan')->insert([
            [
                'id_unduhan' => 1,
                'id_kategori_unduhan' => 1,
                'id_user' => 1,
                'nama_unduhan' => 'Panduan Pengguna Aplikasi',
                'slug' => 'panduan-pengguna-aplikasi',
                'lokasi_file' => '#',
                'deskripsi' => 'Dokumen panduan penggunaan aplikasi untuk pengguna baru',
                'jumlah_unduhan' => 45,
            ],
            [
                'id_unduhan' => 2,
                'id_kategori_unduhan' => 2,
                'id_user' => 1,
                'nama_unduhan' => 'Formulir Pendaftaran',
                'slug' => 'formulir-pendaftaran',
                'lokasi_file' => '#',
                'deskripsi' => 'Formulir pendaftaran untuk menjadi mitra perusahaan',
                'jumlah_unduhan' => 23,
            ],
            [
                'id_unduhan' => 3,
                'id_kategori_unduhan' => 3,
                'id_user' => 2,
                'nama_unduhan' => 'Syarat dan Ketentuan',
                'slug' => 'syarat-dan-ketentuan',
                'lokasi_file' => '#',
                'deskripsi' => 'Dokumen syarat dan ketentuan penggunaan layanan',
                'jumlah_unduhan' => 67,
            ],
        ]);
    }
}