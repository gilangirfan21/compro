<x-layouts.app title="Berita">
    <x-page-header title="Berita" subtitle="Kabar, artikel, dan informasi terbaru dari kami." />

    <section class="py-16">
        <div class="container-page">
            @if ($posts->isEmpty())
                <x-empty-state message="Belum ada berita yang diterbitkan." />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
