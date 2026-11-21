<x-layouts.app title="Portofolio">
    <x-page-header title="Portofolio" subtitle="Proyek-proyek yang telah kami selesaikan bersama klien." />

    <section class="py-16">
        <div class="container-page">
            @if ($categories->isNotEmpty())
                <nav class="mb-8 flex flex-wrap gap-2" aria-label="Filter kategori">
                    <a
                        href="{{ route('portfolios.index') }}"
                        @class([
                            'rounded-full px-4 py-1.5 text-sm font-medium transition',
                            'bg-slate-900 text-white' => ! $activeCategory,
                            'bg-slate-100 text-slate-700 hover:bg-slate-200' => $activeCategory,
                        ])
                    >Semua</a>
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('portfolios.index', ['kategori' => $category]) }}"
                            @class([
                                'rounded-full px-4 py-1.5 text-sm font-medium transition',
                                'bg-slate-900 text-white' => $activeCategory === $category,
                                'bg-slate-100 text-slate-700 hover:bg-slate-200' => $activeCategory !== $category,
                            ])
                        >{{ $category }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($portfolios->isEmpty())
                <x-empty-state message="Belum ada portofolio." />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        <x-portfolio-card :portfolio="$portfolio" />
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $portfolios->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
