@props(['service'])

<article class="flex h-full flex-col rounded-xl border border-slate-200 bg-white p-6 transition hover:border-amber-300 hover:shadow-md">
    @if ($service->image_url)
        <img src="{{ $service->image_url }}" alt="" loading="lazy" class="size-14 rounded-lg object-cover">
    @else
        <span class="grid size-14 place-items-center rounded-lg bg-amber-100 text-amber-700" aria-hidden="true">
            <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
            </svg>
        </span>
    @endif
    <h3 class="mt-5 text-lg font-semibold text-slate-900">{{ $service->title }}</h3>
    @if ($service->description)
        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $service->description }}</p>
    @endif
</article>
