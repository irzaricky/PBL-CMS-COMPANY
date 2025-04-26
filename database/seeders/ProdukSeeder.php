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
            ],
            [
                'id_produk' => 2,
                'id_kategori_produk' => 2,
                'nama_produk' => 'Jasa Konsultasi IT',
                'harga_produk' => 'Rp 500.000/jam',
                'slug' => 'jasa-konsultasi-it',
                'deskripsi_produk' => 'Layanan konsultasi IT untuk membantu perusahaan dalam menentukan solusi teknologi yang tepat.',
            ],
            [
                'id_produk' => 3,
                'id_kategori_produk' => 1,
                'nama_produk' => 'Server Rack',
                'harga_produk' => 'Rp 15.000.000',
                'slug' => 'server-rack',
                'deskripsi_produk' => 'Server rack berkualitas tinggi untuk kebutuhan perusahaan dengan garansi 3 tahun.',
            ],
            [
                'id_produk' => 4,
                'id_kategori_produk' => 3,
                'nama_produk' => 'Sistem Manajemen Inventaris',
                'harga_produk' => 'Rp 2.750.000',
                'slug' => 'sistem-manajemen-inventaris',
                'deskripsi_produk' => 'Sistem lengkap untuk melacak, mengelola, dan mengoptimalkan inventaris perusahaan dengan fitur barcode scanner dan laporan real-time.',
            ],
            [
                'id_produk' => 5,
                'id_kategori_produk' => 3,
                'nama_produk' => 'Aplikasi HR & Kepegawaian',
                'harga_produk' => 'Rp 3.200.000',
                'slug' => 'aplikasi-hr-kepegawaian',
                'deskripsi_produk' => 'Solusi lengkap untuk manajemen sumber daya manusia, termasuk presensi, pengajuan cuti, dan penilaian kinerja karyawan.',
            ],
            [
                'id_produk' => 6,
                'id_kategori_produk' => 2,
                'nama_produk' => 'Layanan Pengembangan Website',
                'harga_produk' => 'Mulai Rp 5.000.000',
                'slug' => 'layanan-pengembangan-website',
                'deskripsi_produk' => 'Jasa pembuatan website profesional dengan desain responsif, optimasi SEO, dan integrasi sistem manajemen konten yang mudah digunakan.',
            ],
            [
                'id_produk' => 7,
                'id_kategori_produk' => 2,
                'nama_produk' => 'Maintenance & Support IT',
                'harga_produk' => 'Rp 2.000.000/bulan',
                'slug' => 'maintenance-support-it',
                'deskripsi_produk' => 'Layanan pemeliharaan dan dukungan IT berkelanjutan untuk memastikan sistem perusahaan Anda berjalan lancar dan aman.',
            ],
            [
                'id_produk' => 8,
                'id_kategori_produk' => 1,
                'nama_produk' => 'Networking Kit Enterprise',
                'harga_produk' => 'Rp 8.500.000',
                'slug' => 'networking-kit-enterprise',
                'deskripsi_produk' => 'Paket perangkat jaringan lengkap untuk kebutuhan perusahaan skala menengah hingga besar, termasuk router, switch, dan access point.',
            ],
            [
                'id_produk' => 9,
                'id_kategori_produk' => 1,
                'nama_produk' => 'Workstation PC Pro',
                'harga_produk' => 'Rp 12.750.000',
                'slug' => 'workstation-pc-pro',
                'deskripsi_produk' => 'Komputer workstation performa tinggi untuk kebutuhan desain grafis, rendering 3D, dan pengembangan perangkat lunak.',
            ],
            [
                'id_produk' => 10,
                'id_kategori_produk' => 3,
                'nama_produk' => 'Sistem Keamanan Data Enterprise',
                'harga_produk' => 'Rp 4.500.000',
                'slug' => 'sistem-keamanan-data-enterprise',
                'deskripsi_produk' => 'Solusi keamanan data komprehensif dengan enkripsi end-to-end, backup otomatis, dan perlindungan dari serangan siber.',
            ],
        ]);
    }
}