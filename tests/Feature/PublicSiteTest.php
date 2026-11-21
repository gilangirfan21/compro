<?php

use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    $this->withoutVite();
});

it('renders every public page with seeded data', function (string $route) {
    $this->seed(ContentSeeder::class);

    $this->get(route($route))
        ->assertOk()
        ->assertSee('PT Nusantara Digital Solusi');
})->with(['home', 'about', 'services', 'portfolios.index', 'posts.index', 'contact']);

it('renders public pages when the database is empty', function (string $route) {
    $this->get(route($route))->assertOk();
})->with(['home', 'about', 'services', 'portfolios.index', 'posts.index', 'contact']);

it('only lists active services', function () {
    Service::factory()->create(['title' => 'Layanan Aktif']);
    Service::factory()->inactive()->create(['title' => 'Layanan Tersembunyi']);

    $this->get(route('services'))
        ->assertSee('Layanan Aktif')
        ->assertDontSee('Layanan Tersembunyi');
});

it('shows an active portfolio and hides an inactive one', function () {
    $active = Portfolio::factory()->create();
    $inactive = Portfolio::factory()->inactive()->create();

    $this->get(route('portfolios.show', $active))->assertOk()->assertSee($active->title);
    $this->get(route('portfolios.show', $inactive))->assertNotFound();
});

it('filters portfolios by category', function () {
    Portfolio::factory()->create(['title' => 'Proyek Web', 'category' => 'Website']);
    Portfolio::factory()->create(['title' => 'Proyek Logo', 'category' => 'Branding']);

    $this->get(route('portfolios.index', ['kategori' => 'Website']))
        ->assertSee('Proyek Web')
        ->assertDontSee('Proyek Logo');
});

it('only shows published posts', function () {
    $published = Post::factory()->create();
    $draft = Post::factory()->draft()->create();
    $scheduled = Post::factory()->scheduled()->create();

    $this->get(route('posts.index'))
        ->assertSee($published->title)
        ->assertDontSee($draft->title)
        ->assertDontSee($scheduled->title);

    $this->get(route('posts.show', $published))->assertOk()->assertSee($published->title);
    $this->get(route('posts.show', $draft))->assertNotFound();
    $this->get(route('posts.show', $scheduled))->assertNotFound();
});

it('sanitizes post content', function () {
    $post = Post::factory()->create(['content' => '<p>Aman</p><script>alert(1)</script>']);

    $this->get(route('posts.show', $post))
        ->assertSee('Aman')
        ->assertDontSee('<script>alert(1)</script>', escape: false);
});

it('stores a valid contact message', function () {
    $this->post(route('contact.store'), [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'phone' => '0812-3456-7890',
        'subject' => 'Penawaran',
        'message' => 'Saya ingin meminta penawaran harga.',
    ])->assertRedirect(route('contact'))->assertSessionHas('status');

    expect(Message::sole())
        ->name->toBe('Budi')
        ->is_read->toBeFalse();
});

it('validates the contact form', function () {
    $this->from(route('contact'))
        ->post(route('contact.store'), ['email' => 'bukan-email', 'message' => 'pendek'])
        ->assertRedirect(route('contact'))
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

    expect(Message::count())->toBe(0);
});

it('silently drops honeypot submissions', function () {
    $this->post(route('contact.store'), [
        'name' => 'Bot',
        'email' => 'bot@example.com',
        'subject' => 'Spam',
        'message' => 'Beli sekarang juga murah!',
        'website' => 'http://spam.test',
    ])->assertRedirect(route('contact'));

    expect(Message::count())->toBe(0);
});

it('rate limits the contact form', function () {
    RateLimiter::clear('contact');

    $payload = [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'subject' => 'Halo',
        'message' => 'Ini pesan percobaan rate limit.',
    ];

    foreach (range(1, 3) as $i) {
        $this->post(route('contact.store'), $payload)->assertSessionHasNoErrors();
    }

    $this->from(route('contact'))
        ->post(route('contact.store'), $payload)
        ->assertRedirect(route('contact'))
        ->assertSessionHasErrors('message');

    expect(Message::count())->toBe(3);
});

it('refreshes the cached settings after saving', function () {
    Setting::create(['company_name' => 'Nama Lama']);
    $this->get(route('home'))->assertSee('Nama Lama');

    Setting::current()->update(['company_name' => 'Nama Baru']);
    $this->get(route('home'))->assertSee('Nama Baru');
});

it('reads cached settings from a serializing cache store', function () {
    config(['cache.default' => 'file']);
    Cache::forget(Setting::CACHE_KEY);

    Setting::create(['company_name' => 'PT Cache', 'social_links' => [['platform' => 'x', 'url' => 'https://x.com/a']]]);

    // First request fills the cache, second one reads it back.
    $this->get(route('home'))->assertOk()->assertSee('PT Cache');
    $this->get(route('home'))->assertOk()->assertSee('PT Cache')->assertSee('X (Twitter)');

    Cache::forget(Setting::CACHE_KEY);
});

it('can run the content seeder twice without duplicating data', function () {
    $this->seed(ContentSeeder::class);
    $this->seed(ContentSeeder::class);

    expect(Setting::count())->toBe(1)
        ->and(Service::count())->toBe(7)
        ->and(Post::count())->toBe(9);
});
