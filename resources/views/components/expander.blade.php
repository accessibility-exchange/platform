<div class="expander" x-data="{expanded: false}">
    <x-heading class="expander__summary" :level="$level">
        <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
            {{ $summary }} <x-heroicon-s-plus x-show="! expanded" aria-hidden="true" class="icon" /><x-heroicon-s-minus x-show="expanded" aria-hidden="true" class="icon" />
        </button>
    </x-heading>
    <div class="expander__content" x-show="expanded">
        {!! $slot ?? '' !!}
    </div>
</div>
