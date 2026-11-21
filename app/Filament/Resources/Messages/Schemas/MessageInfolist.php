<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class MessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('subject')
                            ->label('Subjek')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('message')
                            ->label('Pesan')
                            ->prose()
                            ->formatStateUsing(fn (string $state): string => nl2br(e($state)))
                            ->html(),
                    ]),
                Section::make('Pengirim')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama'),
                        TextEntry::make('email')
                            ->copyable()
                            ->url(fn (string $state): string => "mailto:{$state}"),
                        TextEntry::make('phone')
                            ->label('Telepon')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('created_at')
                            ->label('Diterima')
                            ->dateTime(),
                        IconEntry::make('is_read')
                            ->label('Sudah dibaca')
                            ->boolean(),
                    ]),
            ]);
    }
}
