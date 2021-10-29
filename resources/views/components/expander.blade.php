<div class="expander" x-data="{expanded: false}">
    <x-header class="expander__summary" :level="$level">
        <button x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
            {{ $summary }} <x-heroicon-s-plus x-show="! expanded" aria-hidden="true" class="icon" /><x-heroicon-s-minus x-show="expanded" aria-hidden="true" class="icon" />
        </button>
    </x-header>
    <div class="expander__content" x-show="expanded">
        {!! $slot ?? '' !!}
    </div>
</div>
