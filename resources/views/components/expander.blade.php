@props(['level', 'summary' => '', 'type' => null, 'expanded' => 'false'])
<div {{ $attributes->class(['expander stack', 'expander--disclosure' => $type === 'disclosure'])->whereDoesntStartWith('x-data') }}
    x-data="{ expanded: {{ $expanded }} }">
    <x-heading class="title" :level="$level">
        <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
            <span>{{ $summary }}</span>
            @if ($type === 'disclosure')
                @svg('heroicon-s-chevron-down', 'icon--2xl', ['x-show' => '!expanded'])
                @svg('heroicon-s-chevron-up', 'icon--2xl', ['x-show' => 'expanded', 'x-cloak'])
            @else
                @svg('heroicon-s-plus', 'indicator', ['x-show' => '!expanded'])
                @svg('heroicon-s-minus', 'indicator', ['x-show' => 'expanded', 'x-cloak'])
            @endif
        </button>
    </x-heading>
    <div class="stack" x-show="expanded" x-cloak>
        {{ $slot }}
    </div>
</div>
