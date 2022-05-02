<div class="expander stack" x-data="{expanded: false, initialized: false}" x-init="initialized = true;" x-ref="expander">
    <x-heading class="title" :level="$level">
        <span x-show="!initialized">{{ $summary }}</span>
        <template x-if="initialized">
            <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
                {{ $summary }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" />
            </button>
        </template>
    </x-heading>
    <div class="stack" x-show="initialized ? expanded : true">
        {!! $slot ?? '' !!}
    </div>
</div>
