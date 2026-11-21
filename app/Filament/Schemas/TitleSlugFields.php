<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

/**
 * Title + slug inputs where the slug follows the title until it is edited manually.
 */
class TitleSlugFields
{
    /**
     * @return array<TextInput>
     */
    public static function make(string $titleLabel = 'Judul'): array
    {
        return [
            TextInput::make('title')
                ->label($titleLabel)
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, string $operation, ?string $old, ?string $state): void {
                    if ($operation !== 'create' && filled($get('slug'))) {
                        return;
                    }

                    // Only keep syncing while the slug still matches the previous title.
                    if (filled($get('slug')) && $get('slug') !== Str::slug($old ?? '')) {
                        return;
                    }

                    $set('slug', Str::slug($state ?? ''));
                }),
            TextInput::make('slug')
                ->maxLength(255)
                ->alphaDash()
                ->unique()
                ->helperText('Otomatis dari judul. Kosongkan untuk dibuat otomatis saat disimpan.'),
        ];
    }
}
