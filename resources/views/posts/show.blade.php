<x-layouts.app :title="$post->title" :description="$post->excerpt" :image="$post->thumbnail_url" type="article">
    @push('head')
        <x-json-ld :data="[
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->thumbnail_url,
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'mainEntityOfPage' => route('posts.show', $post),
            'publisher' => array_filter([
                '@type' => 'Organization',
                'name' => $setting->company_name ?: config('app.name'),
                'logo' => $setting->logo_url,
            ]),
        ]" />
    @endpush

    <article>
        <header class="border-b border-slate-200 bg-slate-50">
            <div class="container-page max-w-3xl py-12 sm:py-16">
                <a href="{{ route('posts.index') }}" class="text-sm text-slate-500 hover:text-slate-800">&larr; Berita</a>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $post->title }}</h1>
                <time datetime="{{ $post->published_at->toIso8601String() }}" class="mt-4 block text-sm text-slate-500">
                    {{ $post->published_at->locale('id')->translatedFormat('l, d F Y') }}
                </time>
            </div>
        </header>

        <div class="container-page max-w-3xl py-12">
            @if ($post->thumbnail_url)
                <x-cover :src="$post->thumbnail_url" :alt="$post->title" eager class="mb-10 rounded-xl" />
            @endif

            <div class="rich-content">
                {!! $post->content_html !!}
            </div>
        </div>
    </article>

    @if ($latest->isNotEmpty())
        <section class="bg-slate-50 py-16">
            <div class="container-page">
                <h2 class="text-xl font-semibold text-slate-900">Berita Lainnya</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latest as $item)
                        <x-post-card :post="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
