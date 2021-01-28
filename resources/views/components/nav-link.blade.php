@props(['active'])

@php
$classes = ($active ?? false)
            ? 'active'
            : '';
@endphp

<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
