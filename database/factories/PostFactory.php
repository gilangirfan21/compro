<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->unique()->sentence(fake()->numberBetween(4, 8)), '.');

        $paragraphs = collect(fake()->paragraphs(4))
            ->map(fn (string $paragraph): string => "<p>{$paragraph}</p>")
            ->implode('');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'thumbnail' => null,
            'content' => $paragraphs.'<h2>'.rtrim(fake()->sentence(4), '.').'</h2><p>'.fake()->paragraph().'</p>',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'status' => PostStatus::Published,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
        ]);
    }
}
