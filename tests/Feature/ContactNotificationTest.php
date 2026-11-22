<?php

use App\Models\Message;
use App\Models\Setting;
use App\Notifications\NewContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    Notification::fake();

    $this->payload = [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'subject' => 'Penawaran',
        'message' => 'Saya ingin meminta penawaran harga.',
    ];
});

it('emails the company inbox when a contact message is sent', function () {
    Setting::create(['company_name' => 'PT Contoh', 'email' => 'info@contoh.test']);

    $this->post(route('contact.store'), $this->payload)->assertSessionHasNoErrors();

    Notification::assertSentOnDemand(
        NewContactMessage::class,
        fn (NewContactMessage $notification, array $channels, AnonymousNotifiable $notifiable): bool => $notifiable->routes['mail'] === 'info@contoh.test'
            && $notification->contactMessage->is(Message::sole()),
    );
});

it('lets the admin reply directly to the sender', function () {
    $message = Message::factory()->create(['email' => 'budi@example.com', 'name' => 'Budi']);

    $mail = (new NewContactMessage($message))->toMail(new AnonymousNotifiable);

    expect($mail->replyTo)->toBe([['budi@example.com', 'Budi']])
        ->and($mail->subject)->toContain($message->subject);
});

it('skips the email when no company email is set', function () {
    Setting::create(['company_name' => 'PT Contoh']);

    $this->post(route('contact.store'), $this->payload)->assertSessionHasNoErrors();

    expect(Message::count())->toBe(1);
    Notification::assertNothingSent();
});

it('does not email honeypot submissions', function () {
    Setting::create(['company_name' => 'PT Contoh', 'email' => 'info@contoh.test']);

    $this->post(route('contact.store'), [...$this->payload, 'website' => 'http://spam.test']);

    Notification::assertNothingSent();
});
