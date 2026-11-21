<x-layouts.app title="Tentang Kami" :description="\Illuminate\Support\Str::limit((string) $setting->about, 160)">
    <x-page-header title="Tentang Kami" :subtitle="$setting->tagline" />

    <section class="py-16">
        <div class="container-page grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-semibold text-slate-900">{{ $setting->company_name }}</h2>
                @if ($setting->about)
                    <div class="mt-4 space-y-4 leading-relaxed whitespace-pre-line text-slate-700">{{ $setting->about }}</div>
                @else
                    <x-empty-state class="mt-6" message="Informasi tentang perusahaan belum diisi." />
                @endif
            </div>

            @if ($setting->logo_url)
                <div class="flex items-start justify-center lg:justify-end">
                    <img src="{{ $setting->logo_url }}" alt="{{ $setting->company_name }}" class="max-h-48 w-auto">
                </div>
            @endif
        </div>
    </section>

    @if ($setting->vision || $setting->mission_items)
        <section class="bg-slate-50 py-16">
            <div class="container-page grid gap-8 md:grid-cols-2">
                @if ($setting->vision)
                    <div class="rounded-xl border border-slate-200 bg-white p-8">
                        <p class="text-sm font-semibold tracking-wider text-amber-700 uppercase">Visi</p>
                        <p class="mt-4 text-lg leading-relaxed whitespace-pre-line text-slate-800">{{ $setting->vision }}</p>
                    </div>
                @endif

                @if ($setting->mission_items)
                    <div class="rounded-xl border border-slate-200 bg-white p-8">
                        <p class="text-sm font-semibold tracking-wider text-amber-700 uppercase">Misi</p>
                        <ol class="mt-4 space-y-3">
                            @foreach ($setting->mission_items as $item)
                                <li class="flex gap-3">
                                    <span class="grid size-6 shrink-0 place-items-center rounded-full bg-amber-100 text-xs font-semibold text-amber-800">{{ $loop->iteration }}</span>
                                    <span class="text-slate-700">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>
        </section>
    @endif
</x-layouts.app>
