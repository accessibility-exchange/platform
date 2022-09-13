<div class="stack">
    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="model" :value="$label" />
        <div class="repel">
            <x-hearth-input name="model" type="search" wire:model.defer="query" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div role="alert">
        @if ($results->isNotEmpty())
            <p class="h4">{{ __(':count result for “:query”', ['count' => $results->count(), 'query' => $query]) }}
            </p>
        @endif
    </div>

    @if ($selectable)
        <div class="sr-only" role="alert">
            @if ($selection)
                <p>{{ __(':selection selected.', ['selection' => $selection->name]) }}</p>
            @endif
        </div>
    @endif

    <div class="grid">
        @forelse($results as $result)
            <x-dynamic-component :component="$component" :model="$result" :level="$level" :selected="$selection?->id === $result->id" />
            @if ($selectable)
                <button class="borderless" wire:click="sendSelection({{ $result->id }})">{{ __('Select') }} <span
                        class="sr-only">{{ $result->name }}</span></button>
            @endif
        @empty
            @if ($query)
                <p>{{ __('No results found.') }}</p>
            @endif
        @endforelse
    </div>
</div>
