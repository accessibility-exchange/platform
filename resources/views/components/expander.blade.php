<div class="expander stack" x-data="{ expanded: false }">
    <x-heading class="title" :level="$level">
        <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
            <span>{{ $summary }}</span>
            @svg('heroicon-s-plus', 'indicator', ['x-show' => '!expanded'])
            @svg('heroicon-s-minus', 'indicator', ['x-show' => 'expanded'])
        </button>
    </x-heading>
    <div class="stack" x-show="expanded" x-cloak>
        {!! $slot ?? '' !!}
    </div>
</div>
