@props(['level' => '2'])

<article {{ $attributes->merge(['class' => 'box stack card']) }}>
    <x-heading :level="$level" class="h3">{{ $title }}</x-heading>
    <div class="stack">
        {{ $slot }}
    </div>
</article>
