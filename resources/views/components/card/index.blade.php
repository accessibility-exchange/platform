@props(['level' => '2'])

<article {{ $attributes->merge(['class' => 'box stack card']) }}>
    <x-heading class="h3" :level="$level">{{ $title }}</x-heading>
    <div class="stack">
        {{ $slot }}
    </div>
</article>
