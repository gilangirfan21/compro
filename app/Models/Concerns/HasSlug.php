<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Generates a unique slug from the title when the slug is left empty.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (self $model): void {
            if (blank($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->title);
            }
        });
    }

    public function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->whereKeyNot($this->getKey())->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
