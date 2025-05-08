<?php

namespace Database\Factories;

use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lamaran>
 */
class LamaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Lamaran::class;

    public function definition(): array
    {
        $statusOptions = ['Diproses', 'Diterima', 'Ditolak'];
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-1 month');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        return [
            'id_user' => User::whereIn('id_user', [9, 10, 11, 12, 13])->inRandomOrder()->first()?->id_user ?? rand(9, 13),
            'id_lowongan' => Lowongan::inRandomOrder()->first()?->id_lowongan ?? rand(1, 3),
            'nama_asli' => $this->faker->name(),
            'status_lamaran' => $this->faker->randomElement($statusOptions),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    /**
     * Indicate that the application has been processed
     */
    public function processed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_lamaran' => 'Diproses',
            ];
        });
    }

    /**
     * Indicate that the application has been accepted
     */
    public function accepted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_lamaran' => 'Diterima',
            ];
        });
    }

    /**
     * Indicate that the application has been rejected
     */
    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_lamaran' => 'Ditolak',
            ];
        });
    }
}