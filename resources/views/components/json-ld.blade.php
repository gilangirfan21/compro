{{-- Structured data (schema.org) for search engines. Null values are dropped. --}}
@props(['data'])

<script type="application/ld+json">{!! json_encode(array_filter($data, fn ($value) => filled($value)), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
