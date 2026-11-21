<?php

namespace App\Filament\Resources\Messages\Tables;

use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('is_read')
                    ->label('Dibaca')
                    ->boolean(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight(fn (Message $record): ?FontWeight => $record->is_read ? null : FontWeight::Bold),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(50)
                    ->weight(fn (Message $record): ?FontWeight => $record->is_read ? null : FontWeight::Bold),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Sudah dibaca'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('toggleRead')
                    ->label(fn (Message $record): string => $record->is_read ? 'Tandai belum dibaca' : 'Tandai dibaca')
                    ->icon(fn (Message $record): Heroicon => $record->is_read ? Heroicon::OutlinedEnvelope : Heroicon::OutlinedEnvelopeOpen)
                    ->color('gray')
                    ->authorize('update')
                    ->action(fn (Message $record) => $record->update(['is_read' => ! $record->is_read])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Tandai dibaca')
                        ->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->action(fn (Collection $records) => Message::query()->whereKey($records->modelKeys())->update(['is_read' => true]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
