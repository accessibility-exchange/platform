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

    @forelse($results as $result)
        <div class="stack">
            <p>{{ $result->name }}</p>
        </div>
    @empty
        @if ($query)
            <p>{{ __('No results found.') }}</p>
        @endif
    @endforelse
</div>
