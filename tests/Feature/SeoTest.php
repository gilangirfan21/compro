<?php

use App\Models\Portfolio;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    $this->withoutVite();
});

it('lists public pages and only visible content in the sitemap', function () {
    $activePortfolio = Portfolio::factory()->create();
    $inactivePortfolio = Portfolio::factory()->inactive()->create();
    $published = Post::factory()->create();
    $draft = Post::factory()->draft()->create();
    $scheduled = Post::factory()->scheduled()->create();

    $response = $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    $locations = [];
    foreach (simplexml_load_string($response->getContent())->url as $url) {
        $locations[] = (string) $url->loc;
    }

    expect($locations)
        ->toContain(route('home'), route('contact'))
        ->toContain(route('portfolios.show', $activePortfolio), route('posts.show', $published))
        ->not->toContain(route('portfolios.show', $inactivePortfolio))
        ->not->toContain(route('posts.show', $draft))
        ->not->toContain(route('posts.show', $scheduled));
});

it('blocks all crawlers outside production', function () {
    $this->get(route('robots'))
        ->assertOk()
        ->assertSeeText('Disallow: /')
        ->assertDontSeeText('Sitemap:');
});

it('allows crawlers and points to the sitemap in production', function () {
    app()['env'] = 'production';

    $this->get(route('robots'))
        ->assertOk()
        ->assertSeeText('Disallow: /admin')
        ->assertSeeText('Sitemap: '.route('sitemap'));
});

it('renders meta tags and structured data for a post', function () {
    $post = Post::factory()->create(['title' => 'Judul Berita SEO', 'thumbnail' => 'posts/cover.jpg']);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('posts.show', $post).'">', escape: false)
        ->assertSee('<meta property="og:type" content="article">', escape: false)
        ->assertSee('<meta property="og:image" content="'.$post->thumbnail_url.'">', escape: false)
        ->assertSee('"@type":"Article"', escape: false)
        ->assertSee('"headline":"Judul Berita SEO"', escape: false);
});

it('keeps only content-changing query params in the canonical url', function () {
    $this->get(route('portfolios.index', ['kategori' => 'Website', 'utm_source' => 'iklan']))
        ->assertSee('<link rel="canonical" href="'.route('portfolios.index', ['kategori' => 'Website']).'">', escape: false);

    $this->get(route('posts.index', ['page' => 1]))
        ->assertSee('<link rel="canonical" href="'.route('posts.index').'">', escape: false);
});

it('renders a branded, non-indexable 404 page', function () {
    $this->get('/halaman-yang-tidak-ada')
        ->assertNotFound()
        ->assertSee('Halaman tidak ditemukan')
        ->assertSee('<meta name="robots" content="noindex, follow">', escape: false)
        ->assertDontSee('rel="canonical"', escape: false);
});
