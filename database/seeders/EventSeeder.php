<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan faker default (en_US) untuk bs atau catchprase
        $fakerEN = Faker::create('en_US');
        // Gunakan faker Indonesia untuk data lainnya
        $faker = Faker::create('id_ID');

        $events = [];

        // Generate 20 event dengan Faker
        for ($i = 1; $i <= 20; $i++) {
            $namaEvent = $fakerEN->bs();
            $startDate = $faker->dateTimeBetween('-2 months', '+6 months');
            $endDate = clone $startDate;
            $duration = rand(1, 8); // Durasi dalam jam
            $endDate->modify("+$duration hours");

            // 30% event daring, 70% luring
            $isOnline = $faker->boolean(30);

            if ($isOnline) {
                $lokasi = 'Online via ' . $faker->randomElement(['Zoom', 'Google Meet', 'Microsoft Teams', 'Webex']);
            } else {
                $lokasi = $faker->company() . ', ' . $faker->city();
            }

            $deskripsi = $this->generateEventDescription($faker, $fakerEN);

            $events[] = [
                'id_event' => $i,
                'nama_event' => ucfirst($namaEvent),
                'deskripsi_event' => $deskripsi,
                'lokasi_event' => $lokasi,
                'link_lokasi_event' => 'https://maps.app.goo.gl/TkgARfqzGjPkxFRcA',
                'waktu_start_event' => $startDate,
                'waktu_end_event' => $endDate,
                'jumlah_pendaftar' => rand(0, 200),
                'slug' => Str::slug($namaEvent),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('event')->insert($events);
    }

    /**
     * Generate deskripsi event
     */
    private function generateEventDescription($faker, $fakerEN)
    {
        $deskripsi = $faker->paragraph(rand(2, 4)) . "\n\n";

        // Tambahkan informasi pembicara
        if ($faker->boolean(80)) {
            $deskripsi .= "PEMBICARA:\n\n";
            for ($i = 0; $i < rand(2, 5); $i++) {
                $deskripsi .= "- " . $faker->name() . " (" . $faker->jobTitle() . ")\n";
            }
            $deskripsi .= "\n";
        }

        // Tambahkan agenda
        if ($faker->boolean(90)) {
            $deskripsi .= "AGENDA:\n\n";
            $startHour = rand(8, 13);
            for ($i = 0; $i < rand(3, 6); $i++) {
                $endHour = $startHour + 1;
                $deskripsi .= sprintf(
                    "%02d:00 - %02d:00: %s\n",
                    $startHour,
                    $endHour,
                    $fakerEN->sentence(rand(3, 6))
                );
                $startHour = $endHour;
            }
            $deskripsi .= "\n";
        }

        // Tambahkan pembelajaran
        if ($faker->boolean(70)) {
            $deskripsi .= "YANG AKAN DIPELAJARI:\n\n";
            for ($i = 0; $i < rand(3, 6); $i++) {
                $deskripsi .= "- " . $fakerEN->sentence(rand(3, 8)) . "\n";
            }
            $deskripsi .= "\n";
        }

        // Tambahkan fasilitas
        if ($faker->boolean(75)) {
            $deskripsi .= "FASILITAS:\n\n";
            $facilities = ['Sertifikat', 'Makan Siang', 'Coffee Break', 'Materi Workshop', 'Merchandise', 'Networking Session'];
            $selectedFacilities = $faker->randomElements($facilities, rand(2, count($facilities)));
            foreach ($selectedFacilities as $facility) {
                $deskripsi .= "- " . $facility . "\n";
            }
            $deskripsi .= "\n";
        }

        // Tambahkan catatan penutup
        if ($faker->boolean(70)) {
            $deskripsi .= "CATATAN:\n\n";
            $deskripsi .= $faker->paragraph(rand(1, 2));
        }

        return $deskripsi;
    }
}