@props(['message' => 'Belum ada data untuk ditampilkan.'])

<div {{ $attributes->class('rounded-xl border border-dashed border-slate-300 px-6 py-14 text-center text-sm text-slate-500') }}>
    {{ $message }}
</div>
