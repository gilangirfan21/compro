<?php

namespace App\Filament\Resources\Portfolios\Schemas;

use App\Filament\Schemas\TitleSlugFields;
use App\Models\Portfolio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PortfolioForm
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
                            ->rows(8),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('portfolios')
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('1920')
                            ->automaticallyResizeImagesToHeight('1920')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->visibility('public')
                            ->maxSize(4096),
                        TextInput::make('category')
                            ->label('Kategori')
                            ->maxLength(100)
                            ->datalist(fn (): array => Portfolio::query()
                                ->whereNotNull('category')
                                ->distinct()
                                ->orderBy('category')
                                ->pluck('category')
                                ->all()),
                        DatePicker::make('date')
                            ->label('Tanggal'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
