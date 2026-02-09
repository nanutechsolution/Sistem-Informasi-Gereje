<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Generate UUID otomatis ditangani Model
            'nomor_kk' => $this->faker->unique()->numerify('53#############'), // Format KK Sumba Timur biasanya diawali 53
            'kepala_keluarga' => $this->faker->name('male'),
            'wilayah' => $this->faker->numberBetween(1, 10),
            'alamat' => $this->faker->address(),
            'status' => 'aktif',
            'keterangan' => $this->faker->optional()->sentence(),
        ];
    }
}