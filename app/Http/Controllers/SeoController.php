<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Post;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $staticPages = collect(['home', 'about', 'services', 'portfolios.index', 'posts.index', 'contact'])
            ->map(fn (string $route): array => ['loc' => route($route), 'lastmod' => null]);

        $portfolios = Portfolio::query()
            ->active()
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (Portfolio $portfolio): array => [
                'loc' => route('portfolios.show', $portfolio),
                'lastmod' => $portfolio->updated_at,
            ]);

        $posts = Post::query()
            ->published()
            ->latest('published_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (Post $post): array => [
                'loc' => route('posts.show', $post),
                'lastmod' => $post->updated_at,
            ]);

        return response()
            ->view('seo.sitemap', ['urls' => $staticPages->concat($portfolios)->concat($posts)])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', '', 'Sitemap: '.route('sitemap')]
            // Keep staging/local environments out of search engines.
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
