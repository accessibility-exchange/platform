@props(['active'])

@php
$current = ($active ?? false)
            ? ['aria-current' => 'page']
            : [];
@endphp

<li>
    <a {{ $attributes->merge($current) }}>
        {{ $slot }}
    </a>
</li>
