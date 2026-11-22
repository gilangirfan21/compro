<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\Setting;
use App\Notifications\NewContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        // Honeypot filled in by a bot: pretend success without storing anything.
        if ($request->filled('website')) {
            return to_route('contact')->with('status', 'Terima kasih, pesan Anda sudah kami terima.');
        }

        $message = Message::create($request->safe()->except('website'));

        // Notify the company inbox set in "Pengaturan Website", if any. Queued, so the visitor never waits on SMTP.
        if ($recipient = Setting::cached()->email) {
            Notification::route('mail', $recipient)->notify(new NewContactMessage($message));
        }

        return to_route('contact')->with('status', 'Terima kasih, pesan Anda sudah kami terima. Kami akan segera menghubungi Anda.');
    }
}
