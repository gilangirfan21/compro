{{-- Error page rendered inside the public layout. Only for errors where the app (DB, cache) still works. --}}
@props(['code', 'title', 'message'])

<x-layouts.app :title="$title" noindex>
    <section class="py-24 sm:py-32">
        <div class="container-page max-w-2xl text-center">
            <p class="text-sm font-semibold tracking-wider text-amber-600 uppercase">Error {{ $code }}</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $title }}</h1>
            <p class="mt-4 text-base leading-relaxed text-slate-600">{{ $message }}</p>

            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-primary">Kembali ke Beranda</a>
                <a href="{{ route('contact') }}" class="btn-secondary">Hubungi Kami</a>
            </div>
        </div>
    </section>
</x-layouts.app>
