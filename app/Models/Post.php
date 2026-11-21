<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Models\Concerns\HasSlug;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'thumbnail', 'content', 'published_at', 'status'])]
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, HasSlug;

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            if ($post->status === PostStatus::Published && blank($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    /**
     * Posts that are published and whose publish date has passed.
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', PostStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @return Attribute<?string, never>
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->thumbnail ? Storage::disk('public')->url($this->thumbnail) : null);
    }

    /**
     * Sanitized HTML of the rich editor content, safe to echo unescaped.
     *
     * @return Attribute<string, never>
     */
    protected function contentHtml(): Attribute
    {
        return Attribute::get(fn (): string => $this->contentRenderer()->toHtml());
    }

    /**
     * @return Attribute<string, never>
     */
    protected function excerpt(): Attribute
    {
        return Attribute::get(fn (): string => Str::limit(Str::squish($this->contentRenderer()->toText()), 160));
    }

    protected function contentRenderer(): RichContentRenderer
    {
        return RichContentRenderer::make($this->content)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsVisibility('public');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => PostStatus::class,
        ];
    }
}
