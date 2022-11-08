@props([
    'level' => '2',
    'titleClass' => 'h3',
])

<article {{ $attributes->merge(['class' => 'stack card']) }}>
    <x-heading :class="$titleClass" :level="$level">{{ $title }}</x-heading>
    <div class="stack">
        {{ $slot }}
    </div>
</article>
