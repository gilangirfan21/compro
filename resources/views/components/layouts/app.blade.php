@props(['title' => null, 'description' => null])

@php
    $siteName = $setting->company_name ?: config('app.name');
    $pageTitle = $title ? "{$title} - {$siteName}" : ($setting->tagline ? "{$siteName} - {$setting->tagline}" : $siteName);
    $metaDescription = $description ?: ($setting->tagline ?: \Illuminate\Support\Str::limit((string) $setting->about, 160));

    $navigation = [
        ['label' => 'Beranda', 'route' => 'home', 'active' => 'home'],
        ['label' => 'Tentang', 'route' => 'about', 'active' => 'about'],
        ['label' => 'Layanan', 'route' => 'services', 'active' => 'services'],
        ['label' => 'Portofolio', 'route' => 'portfolios.index', 'active' => 'portfolios.*'],
        ['label' => 'Berita', 'route' => 'posts.index', 'active' => 'posts.*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $pageTitle }}</title>
        @if ($metaDescription)
            <meta name="description" content="{{ $metaDescription }}">
        @endif
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:site_name" content="{{ $siteName }}">
        @if ($setting->logo_url)
            <link rel="icon" href="{{ $setting->logo_url }}">
        @endif

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-white font-sans text-slate-700 antialiased">
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="container-page flex h-16 items-center justify-between gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 font-semibold text-slate-900">
                    @if ($setting->logo_url)
                        <img src="{{ $setting->logo_url }}" alt="{{ $siteName }}" class="h-9 w-auto">
                    @else
                        <span class="grid size-9 place-items-center rounded-lg bg-amber-400 text-sm font-bold text-slate-900">
                            {{ \Illuminate\Support\Str::of($siteName)->explode(' ')->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->implode('') }}
                        </span>
                    @endif
                    <span class="text-lg">{{ $siteName }}</span>
                </a>

                <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @class([
                                'rounded-md px-3 py-2 text-sm font-medium transition',
                                'text-slate-900 bg-slate-100' => request()->routeIs($item['active']),
                                'text-slate-600 hover:text-slate-900 hover:bg-slate-50' => ! request()->routeIs($item['active']),
                            ])
                            @if (request()->routeIs($item['active'])) aria-current="page" @endif
                        >{{ $item['label'] }}</a>
                    @endforeach
                    <a href="{{ route('contact') }}" class="btn-primary ml-2">Hubungi Kami</a>
                </nav>

                <button
                    type="button"
                    class="inline-flex size-10 items-center justify-center rounded-md text-slate-700 hover:bg-slate-100 md:hidden"
                    data-menu-toggle
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                >
                    <span class="sr-only">Buka menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <nav id="mobile-menu" class="hidden border-t border-slate-200 bg-white md:hidden" aria-label="Navigasi mobile">
                <div class="container-page flex flex-col gap-1 py-3">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @class([
                                'rounded-md px-3 py-2 text-base font-medium',
                                'bg-slate-100 text-slate-900' => request()->routeIs($item['active']),
                                'text-slate-600 hover:bg-slate-50' => ! request()->routeIs($item['active']),
                            ])
                        >{{ $item['label'] }}</a>
                    @endforeach
                    <a href="{{ route('contact') }}" class="btn-primary mt-2 justify-center">Hubungi Kami</a>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-slate-900 text-slate-300">
            <div class="container-page grid gap-10 py-14 md:grid-cols-3">
                <div>
                    <p class="text-lg font-semibold text-white">{{ $siteName }}</p>
                    @if ($setting->tagline)
                        <p class="mt-2 text-sm text-slate-400">{{ $setting->tagline }}</p>
                    @endif

                    @if (filled($setting->social_links))
                        <ul class="mt-5 flex flex-wrap gap-2">
                            @foreach ($setting->social_links as $link)
                                <li>
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-block rounded-full border border-slate-700 px-3 py-1 text-xs font-medium text-slate-300 transition hover:border-amber-400 hover:text-amber-400"
                                    >{{ \App\Models\Setting::SOCIAL_PLATFORMS[$link['platform']] ?? ucfirst($link['platform']) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div>
                    <p class="text-sm font-semibold tracking-wider text-white uppercase">Menu</p>
                    <ul class="mt-4 grid grid-cols-2 gap-2 text-sm">
                        @foreach ($navigation as $item)
                            <li><a href="{{ route($item['route']) }}" class="hover:text-white">{{ $item['label'] }}</a></li>
                        @endforeach
                        <li><a href="{{ route('contact') }}" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-sm font-semibold tracking-wider text-white uppercase">Kontak</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        @if ($setting->address)
                            <li class="whitespace-pre-line">{{ $setting->address }}</li>
                        @endif
                        @if ($setting->phone)
                            <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $setting->phone) }}" class="hover:text-white">{{ $setting->phone }}</a></li>
                        @endif
                        @if ($setting->email)
                            <li><a href="mailto:{{ $setting->email }}" class="hover:text-white">{{ $setting->email }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800">
                <p class="container-page py-6 text-xs text-slate-500">
                    &copy; {{ now()->year }} {{ $siteName }}. Hak cipta dilindungi.
                </p>
            </div>
        </footer>
    </body>
</html>
