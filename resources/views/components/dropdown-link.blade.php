@props(['active'])

@php
$current = ($active ?? false)
            ? ['aria-current' => 'page']
            : [];
@endphp

<a {{ $attributes->merge($current) }}>{{ $slot }}</a>
