@props(['title', 'subtitle' => null])

<section class="border-b border-slate-200 bg-slate-50">
    <div class="container-page py-12 sm:py-16">
        @isset($breadcrumb)
            <div class="mb-3 text-sm text-slate-500">{{ $breadcrumb }}</div>
        @endisset
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-3 max-w-2xl text-base text-slate-600 sm:text-lg">{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
