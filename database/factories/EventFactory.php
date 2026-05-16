<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'client_name' => fake()->name(),
            'client_phone' => fake()->phoneNumber(),
            'client_email' => fake()->email(),
            'event_type' => fake()->randomElement(array_keys(Event::TYPES)),
            'event_date' => fake()->dateTimeBetween('-1 month', '+3 months')->format('Y-m-d'),
            'event_time' => fake()->time('H:i'),
            'people_count' => fake()->numberBetween(5, 100),
            'status' => 'new',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
