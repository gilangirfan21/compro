<x-layouts.app :title="$portfolio->title" :description="\Illuminate\Support\Str::limit((string) $portfolio->description, 160)">
    <x-page-header :title="$portfolio->title">
        <x-slot:breadcrumb>
            <a href="{{ route('portfolios.index') }}" class="hover:text-slate-800">Portofolio</a>
            @if ($portfolio->category)
                <span class="mx-1">/</span>
                <a href="{{ route('portfolios.index', ['kategori' => $portfolio->category]) }}" class="hover:text-slate-800">{{ $portfolio->category }}</a>
            @endif
        </x-slot:breadcrumb>

        @if ($portfolio->date)
            <p class="mt-3 text-sm text-slate-500">
                <time datetime="{{ $portfolio->date->toDateString() }}">{{ $portfolio->date->locale('id')->translatedFormat('d F Y') }}</time>
            </p>
        @endif
    </x-page-header>

    <section class="py-12">
        <div class="container-page max-w-4xl">
            @if ($portfolio->image_url)
                <x-cover :src="$portfolio->image_url" :alt="$portfolio->title" class="rounded-xl" />
            @endif

            @if ($portfolio->description)
                <div class="mt-8 leading-relaxed whitespace-pre-line text-slate-700">{{ $portfolio->description }}</div>
            @endif

            <div class="mt-10">
                <a href="{{ route('portfolios.index') }}" class="btn-secondary">&larr; Kembali ke portofolio</a>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="bg-slate-50 py-16">
            <div class="container-page">
                <h2 class="text-xl font-semibold text-slate-900">Portofolio Lainnya</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-portfolio-card :portfolio="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
