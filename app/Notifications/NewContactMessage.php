<?php

namespace App\Notifications;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $contactMessage) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->contactMessage;

        return (new MailMessage)
            ->subject('Pesan baru dari website: '.$message->subject)
            ->replyTo($message->email, $message->name)
            ->greeting('Ada pesan baru!')
            ->line("**Dari:** {$message->name} ({$message->email})")
            ->when($message->phone, fn (MailMessage $mail) => $mail->line("**Telepon:** {$message->phone}"))
            ->line("**Subjek:** {$message->subject}")
            ->line('**Pesan:**')
            ->line($message->message)
            ->action('Lihat di Panel Admin', MessageResource::getUrl('view', ['record' => $message], panel: 'admin'))
            ->salutation('Balas email ini untuk langsung membalas ke pengirim.');
    }
}
