<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\Message;
use Filament\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestMessages extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Pesan Terbaru';

    public static function canView(): bool
    {
        return auth()->user()?->can('viewAny', Message::class) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Message::query()->latest()->limit(5))
            ->paginated(false)
            ->emptyStateHeading('Belum ada pesan masuk')
            ->recordUrl(fn (Message $record): string => MessageResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->description(fn (Message $record): string => $record->email)
                    ->weight(fn (Message $record): ?FontWeight => $record->is_read ? null : FontWeight::Bold),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(40)
                    ->weight(fn (Message $record): ?FontWeight => $record->is_read ? null : FontWeight::Bold),
                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->headerActions([
                Action::make('all')
                    ->label('Lihat semua')
                    ->link()
                    ->url(MessageResource::getUrl('index')),
            ]);
    }
}
