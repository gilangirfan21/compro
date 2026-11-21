<x-layouts.app title="Layanan">
    <x-page-header title="Layanan Kami" subtitle="Solusi menyeluruh yang kami tawarkan untuk mendukung bisnis Anda." />

    <section class="py-16">
        <div class="container-page">
            @if ($services->isEmpty())
                <x-empty-state message="Belum ada layanan." />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            @endif

            <div class="mt-14 rounded-xl bg-slate-50 p-8 text-center">
                <p class="text-lg font-semibold text-slate-900">Butuh layanan yang tidak tercantum?</p>
                <p class="mt-1 text-slate-600">Hubungi kami dan ceritakan kebutuhan Anda.</p>
                <a href="{{ route('contact') }}" class="btn-primary mt-5">Hubungi Kami</a>
            </div>
        </div>
    </section>
</x-layouts.app>
