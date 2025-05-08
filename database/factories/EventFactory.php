<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;

    public function definition(): array
    {
        $namaEvent = $this->faker->unique()->bs();
        $startDate = $this->faker->dateTimeBetween('-2 months', '+6 months');
        $endDate = clone $startDate;
        $duration = rand(1, 8); // Durasi dalam jam
        $endDate->modify("+$duration hours");

        // 30% event daring, 70% luring
        $isOnline = $this->faker->boolean(30);
        if ($isOnline) {
            $lokasi = 'Online via ' . $this->faker->randomElement(['Zoom', 'Google Meet', 'Microsoft Teams', 'Webex']);
        } else {
            $lokasi = $this->faker->company() . ', ' . $this->faker->city();
        }

        return [
            'nama_event' => ucfirst($namaEvent),
            'deskripsi_event' => $this->generateEventDescription(),
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

    /**
     * Generate deskripsi event
     */
    private function generateEventDescription(): string
    {
        $deskripsi = $this->faker->paragraph(rand(2, 4)) . "\n\n";

        // Tambahkan informasi pembicara
        if ($this->faker->boolean(80)) {
            $deskripsi .= "PEMBICARA:\n\n";
            for ($i = 0; $i < rand(2, 5); $i++) {
                $deskripsi .= "- " . $this->faker->name() . " (" . $this->faker->jobTitle() . ")\n";
            }
            $deskripsi .= "\n";
        }

        // Tambahkan agenda
        if ($this->faker->boolean(90)) {
            $deskripsi .= "AGENDA:\n\n";
            $startHour = rand(8, 13);
            for ($i = 0; $i < rand(3, 6); $i++) {
                $endHour = $startHour + 1;
                $deskripsi .= sprintf(
                    "%02d:00 - %02d:00: %s\n",
                    $startHour,
                    $endHour,
                    $this->faker->sentence(rand(3, 6))
                );
                $startHour = $endHour;
            }
            $deskripsi .= "\n";
        }

        // Tambahkan pembelajaran
        if ($this->faker->boolean(70)) {
            $deskripsi .= "YANG AKAN DIPELAJARI:\n\n";
            for ($i = 0; $i < rand(3, 6); $i++) {
                $deskripsi .= "- " . $this->faker->sentence(rand(3, 8)) . "\n";
            }
            $deskripsi .= "\n";
        }

        // Tambahkan fasilitas
        if ($this->faker->boolean(75)) {
            $deskripsi .= "FASILITAS:\n\n";
            $facilities = ['Sertifikat', 'Makan Siang', 'Coffee Break', 'Materi Workshop', 'Merchandise', 'Networking Session'];
            $selectedFacilities = $this->faker->randomElements($facilities, rand(2, count($facilities)));
            foreach ($selectedFacilities as $facility) {
                $deskripsi .= "- " . $facility . "\n";
            }
            $deskripsi .= "\n";
        }

        // Tambahkan catatan penutup
        if ($this->faker->boolean(70)) {
            $deskripsi .= "CATATAN:\n\n";
            $deskripsi .= $this->faker->paragraph(rand(1, 2));
        }

        return $deskripsi;
    }
}