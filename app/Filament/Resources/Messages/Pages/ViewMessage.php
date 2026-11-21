<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Opening a message marks it as read.
        if (! $this->getRecord()->is_read) {
            $this->getRecord()->update(['is_read' => true]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAsUnread')
                ->label('Tandai belum dibaca')
                ->icon(Heroicon::OutlinedEnvelope)
                ->color('gray')
                ->authorize('update')
                ->action(function (Message $record): void {
                    $record->update(['is_read' => false]);

                    $this->redirect(MessageResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
