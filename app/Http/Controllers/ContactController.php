<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

        Message::create($request->safe()->except('website'));

        return to_route('contact')->with('status', 'Terima kasih, pesan Anda sudah kami terima. Kami akan segera menghubungi Anda.');
    }
}
