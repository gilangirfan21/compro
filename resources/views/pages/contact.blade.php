<x-layouts.app title="Kontak">
    <x-page-header title="Hubungi Kami" subtitle="Punya pertanyaan atau ingin bekerja sama? Kirimkan pesan melalui form di bawah." />

    <section class="py-16">
        <div class="container-page grid gap-12 lg:grid-cols-3">
            <div class="space-y-6">
                @if ($setting->address)
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Alamat</p>
                        <p class="mt-1 text-sm whitespace-pre-line text-slate-600">{{ $setting->address }}</p>
                    </div>
                @endif
                @if ($setting->phone)
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Telepon</p>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $setting->phone) }}" class="mt-1 block text-sm text-slate-600 hover:text-amber-700">{{ $setting->phone }}</a>
                    </div>
                @endif
                @if ($setting->email)
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Email</p>
                        <a href="mailto:{{ $setting->email }}" class="mt-1 block text-sm text-slate-600 hover:text-amber-700">{{ $setting->email }}</a>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-2">
                @if (session('status'))
                    <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="grid gap-5 sm:grid-cols-2" novalidate>
                    @csrf

                    {{-- Honeypot: hidden from humans, bots tend to fill it in. --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    @php
                        $fields = [
                            ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true, 'autocomplete' => 'name'],
                            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'autocomplete' => 'email'],
                            ['name' => 'phone', 'label' => 'Telepon', 'type' => 'tel', 'required' => false, 'autocomplete' => 'tel'],
                            ['name' => 'subject', 'label' => 'Subjek', 'type' => 'text', 'required' => true, 'autocomplete' => 'off'],
                        ];
                    @endphp

                    @foreach ($fields as $field)
                        <div>
                            <label for="{{ $field['name'] }}" class="block text-sm font-medium text-slate-800">
                                {{ $field['label'] }}
                                @unless ($field['required'])
                                    <span class="font-normal text-slate-400">(opsional)</span>
                                @endunless
                            </label>
                            <input
                                type="{{ $field['type'] }}"
                                id="{{ $field['name'] }}"
                                name="{{ $field['name'] }}"
                                value="{{ old($field['name']) }}"
                                autocomplete="{{ $field['autocomplete'] }}"
                                @required($field['required'])
                                @error($field['name']) aria-invalid="true" aria-describedby="{{ $field['name'] }}-error" @enderror
                                @class(['form-input mt-1', 'border-red-400' => $errors->has($field['name'])])
                            >
                            @error($field['name'])
                                <p id="{{ $field['name'] }}-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm font-medium text-slate-800">Pesan</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            required
                            @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                            @class(['form-input mt-1', 'border-red-400' => $errors->has('message')])
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p id="message-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-primary">Kirim Pesan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
