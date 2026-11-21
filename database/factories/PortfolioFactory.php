<?php

namespace Database\Factories;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Portfolio>
 */
class PortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title(fake()->unique()->words(fake()->numberBetween(2, 4), true));

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(['Website', 'Aplikasi Mobile', 'Branding', 'Sistem Informasi']),
            'image' => null,
            'description' => implode("\n\n", fake()->paragraphs(3)),
            'date' => fake()->dateTimeBetween('-2 years', 'now'),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
