<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['company_name', 'tagline', 'about', 'vision', 'mission', 'address', 'phone', 'email', 'logo', 'social_links'])]
class Setting extends Model
{
    /**
     * Get the single settings record, or an unsaved default instance.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new static(['company_name' => config('app.name')]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }
}
