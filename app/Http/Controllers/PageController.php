<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'services' => Service::query()->active()->orderBy('sort_order')->limit(6)->get(),
            'portfolios' => Portfolio::query()->active()->latest('date')->limit(6)->get(),
            'posts' => Post::query()->published()->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function services(): View
    {
        return view('pages.services', [
            'services' => Service::query()->active()->orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }
}
