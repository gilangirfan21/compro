<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Schemas\TitleSlugFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        ...TitleSlugFields::make(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(6),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Ikon / Gambar')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('services')
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('800')
                            ->automaticallyResizeImagesToHeight('800')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->visibility('public')
                            ->maxSize(2048),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
