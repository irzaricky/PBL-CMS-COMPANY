<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('event')->insert([
            [
                'id_event' => 1,
                'nama_event' => 'Webinar Teknologi AI',
                'deskripsi_event' => 'Webinar tentang penerapan AI dalam bisnis dengan pembicara pakar industri',
                'lokasi_event' => 'Online via Zoom',
                'link_lokasi_event' => '#',
                'waktu_start_event' => now()->addDays(7)->setTime(10, 0),
                'waktu_end_event' => now()->addDays(7)->setTime(12, 0),
                'link_daftar_event' => '#',
                'slug' => 'webinar-teknologi-ai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_event' => 2,
                'nama_event' => 'Workshop Pengembangan Web',
                'deskripsi_event' => 'Workshop hands-on tentang pengembangan web menggunakan Laravel',
                'lokasi_event' => 'Kantor PT Teknologi Maju Indonesia, Jakarta',
                'link_lokasi_event' => '#',
                'waktu_start_event' => now()->addDays(14)->setTime(9, 0),
                'waktu_end_event' => now()->addDays(14)->setTime(16, 0),
                'link_daftar_event' => '#',
                'slug' => 'workshop-pengembangan-web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_event' => 3,
                'nama_event' => 'Tech Meetup 2025',
                'deskripsi_event' => 'Pertemuan tahunan untuk membahas tren teknologi terbaru dan networking',
                'lokasi_event' => 'Hotel Grand Indonesia, Jakarta',
                'link_lokasi_event' => '#',
                'waktu_start_event' => now()->addDays(30)->setTime(13, 0),
                'waktu_end_event' => now()->addDays(30)->setTime(17, 0),
                'link_daftar_event' => '#',
                'slug' => 'tech-meetup-2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_event' => 4,
                'nama_event' => 'Tech Meetup 2025 old',
                'deskripsi_event' => 'Pertemuan tahunan untuk membahas tren teknologi terbaru dan networking',
                'lokasi_event' => 'Hotel Grand Indonesia, Jakarta',
                'link_lokasi_event' => '#',
                'waktu_start_event' => now()->subDays(30)->setTime(13, 0),
                'waktu_end_event' => now()->subDays(30)->setTime(17, 0),
                'link_daftar_event' => '#',
                'slug' => 'tech-meetup-2025-past',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}