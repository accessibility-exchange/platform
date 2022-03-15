@props(['active'])

@php
$current = ($active ?? false)
            ? ['aria-current' => 'page', 'class' => 'nav-link']
            : ['class' => 'nav-link'];
@endphp

<a {{ $attributes->merge($current) }}>
    {{ $slot }}
</a>
