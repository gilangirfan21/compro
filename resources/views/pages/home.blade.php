<x-layouts.app>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(251,191,36,0.18),transparent_60%)]" aria-hidden="true"></div>
        <div class="container-page relative py-20 sm:py-28">
            <p class="text-sm font-semibold tracking-wider text-amber-400 uppercase">{{ $setting->company_name }}</p>
            <h1 class="mt-4 max-w-3xl text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                {{ $setting->tagline ?: 'Mitra terpercaya untuk pertumbuhan bisnis Anda' }}
            </h1>
            @if ($setting->about)
                <p class="mt-6 max-w-2xl text-base leading-relaxed text-slate-300 sm:text-lg">
                    {{ \Illuminate\Support\Str::limit($setting->about, 220) }}
                </p>
            @endif
            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('services') }}" class="btn-primary">Lihat Layanan</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-md border border-slate-600 px-4 py-2 text-sm font-semibold text-white transition hover:border-slate-400">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="py-16 sm:py-20">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Layanan Kami</h2>
                    <p class="mt-2 text-slate-600">Solusi yang kami tawarkan untuk kebutuhan Anda.</p>
                </div>
                <a href="{{ route('services') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-600">Semua layanan &rarr;</a>
            </div>

            @if ($services->isEmpty())
                <x-empty-state class="mt-8" message="Belum ada layanan." />
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Portfolio --}}
    <section class="bg-slate-50 py-16 sm:py-20">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Portofolio Terbaru</h2>
                    <p class="mt-2 text-slate-600">Beberapa proyek yang telah kami kerjakan.</p>
                </div>
                <a href="{{ route('portfolios.index') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-600">Semua portofolio &rarr;</a>
            </div>

            @if ($portfolios->isEmpty())
                <x-empty-state class="mt-8" message="Belum ada portofolio." />
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        <x-portfolio-card :portfolio="$portfolio" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- News --}}
    @if ($posts->isNotEmpty())
        <section class="py-16 sm:py-20">
            <div class="container-page">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Berita Terkini</h2>
                        <p class="mt-2 text-slate-600">Kabar dan informasi terbaru dari kami.</p>
                    </div>
                    <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-600">Semua berita &rarr;</a>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="pb-16 sm:pb-20">
        <div class="container-page">
            <div class="flex flex-col items-start justify-between gap-6 rounded-2xl bg-amber-400 px-8 py-10 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Siap memulai proyek Anda?</h2>
                    <p class="mt-1 text-slate-800">Ceritakan kebutuhan Anda, tim kami akan segera menghubungi.</p>
                </div>
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Kirim Pesan
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
