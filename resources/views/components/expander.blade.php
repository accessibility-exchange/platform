<div class="expander stack" x-data="{ expanded: false }">
    <x-heading class="title" :level="$level">
        <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
            {{ $summary }}
            <x-heroicon-s-plus class="indicator" aria-hidden="true" x-show="!expanded" />
            <x-heroicon-s-minus class="indicator" aria-hidden="true" x-show="expanded" />
        </button>
    </x-heading>
    <div class="stack" x-show="expanded" x-cloak>
        {!! $slot ?? '' !!}
    </div>
</div>
