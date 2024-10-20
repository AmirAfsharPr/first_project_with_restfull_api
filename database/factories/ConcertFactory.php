<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConcertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'artist_id' => Artist::factory()->create(),
            'title' => 'this is face concert',
            'description' => 'this concert is amazing and full of joy',
            'starts_at' => now()->format('Y-m-d'),
            'ends_at' => now()->addWeek()->format('Y-m-d'),
            'is_published' => true,
        ];
    }
}
