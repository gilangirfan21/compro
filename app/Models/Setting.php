<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

#[Fillable(['company_name', 'tagline', 'about', 'vision', 'mission', 'address', 'phone', 'email', 'logo', 'social_links'])]
class Setting extends Model
{
    public const CACHE_KEY = 'settings.current';

    public const SOCIAL_PLATFORMS = [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'x' => 'X (Twitter)',
        'linkedin' => 'LinkedIn',
        'youtube' => 'YouTube',
        'tiktok' => 'TikTok',
        'whatsapp' => 'WhatsApp',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Get the single settings record, or an unsaved default instance.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new static(['company_name' => config('app.name')]);
    }

    /**
     * Cached version of current() for the public site.
     */
    public static function cached(): self
    {
        // Cache raw attributes, not the model: the cache refuses to unserialize objects by default.
        $attributes = Cache::rememberForever(
            self::CACHE_KEY,
            fn (): ?array => static::query()->first()?->getAttributes(),
        );

        return $attributes
            ? (new static)->newFromBuilder($attributes)
            : static::current();
    }

    /**
     * @return Attribute<?string, never>
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->logo ? Storage::disk('public')->url($this->logo) : null);
    }

    /**
     * Mission text split into one item per non-empty line.
     *
     * @return Attribute<array<int, string>, never>
     */
    protected function missionItems(): Attribute
    {
        return Attribute::get(fn (): array => collect(preg_split('/\R/', (string) $this->mission))
            // Strip list markers such as "-", "*", "•", "1." or "1)".
            ->map(fn (string $line): string => trim(preg_replace('/^\s*(?:[-*•]|\d+[.)])\s*/u', '', $line)))
            ->filter()
            ->values()
            ->all());
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
