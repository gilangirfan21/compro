@props(['post'])

<article class="group flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:shadow-md">
    <a href="{{ route('posts.show', $post) }}" class="flex h-full flex-col">
        <x-cover :src="$post->thumbnail_url" :alt="$post->title" />
        <div class="flex flex-1 flex-col p-5">
            <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs text-slate-500">
                {{ $post->published_at->locale('id')->translatedFormat('d F Y') }}
            </time>
            <h3 class="mt-2 font-semibold text-slate-900 group-hover:text-amber-700">{{ $post->title }}</h3>
            <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $post->excerpt }}</p>
            <span class="mt-auto pt-4 text-sm font-medium text-amber-700">Baca selengkapnya &rarr;</span>
        </div>
    </a>
</article>
