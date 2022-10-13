<div class="expander stack" x-data="{ expanded: false }">
    <x-heading class="title" :level="$level">
        <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
            {{ $summary }}
            <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" />
        </button>
    </x-heading>
    <div class="stack" x-show="expanded">
        {!! $slot ?? '' !!}
    </div>
</div>
