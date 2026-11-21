<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->optional()->numerify('08##########'),
            'subject' => rtrim(fake()->sentence(fake()->numberBetween(3, 6)), '.'),
            'message' => fake()->paragraphs(fake()->numberBetween(1, 3), true),
            'is_read' => false,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => ['is_read' => true]);
    }
}
