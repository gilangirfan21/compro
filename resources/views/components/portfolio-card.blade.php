@props(['portfolio'])

<article class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:shadow-md">
    <a href="{{ route('portfolios.show', $portfolio) }}" class="block">
        <x-cover :src="$portfolio->image_url" :alt="$portfolio->title" class="transition duration-300 group-hover:opacity-90" />
        <div class="p-5">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                @if ($portfolio->category)
                    <span class="rounded-full bg-amber-100 px-2 py-0.5 font-medium text-amber-800">{{ $portfolio->category }}</span>
                @endif
                @if ($portfolio->date)
                    <time datetime="{{ $portfolio->date->toDateString() }}">{{ $portfolio->date->locale('id')->translatedFormat('M Y') }}</time>
                @endif
            </div>
            <h3 class="mt-2 font-semibold text-slate-900 group-hover:text-amber-700">{{ $portfolio->title }}</h3>
        </div>
    </a>
</article>
