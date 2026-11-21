<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('posts.index', [
            'posts' => Post::query()->published()->latest('published_at')->paginate(9),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless(Post::query()->published()->whereKey($post->getKey())->exists(), 404);

        return view('posts.show', [
            'post' => $post,
            'latest' => Post::query()
                ->published()
                ->whereKeyNot($post->getKey())
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
