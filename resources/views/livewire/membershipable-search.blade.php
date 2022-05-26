<div class="stack">
    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="model" :value="__('Search')" />
        <div class="repel">
            <x-hearth-input type="search" name="model" wire:model.defer="query" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div role="alert">
    @if($results->isNotEmpty())
    <p class="h4">{{ __(':count result for “:query”', ['count'=> $results->count(), 'query' => $query]) }}</p>
    @endif
    </div>

    @forelse($results as $result)
        <div class="stack">
            @if($membershipable === 'App\Models\Organization')
                <x-organization-card level="3" :organization="$result" />
                <form action="{{ localized_route('organizations.join', $result) }}" method="POST">
                    @csrf
                    <button>{{ __('Request to join') }}</button>
                </form>
            @elseif($membershipable === 'App\Models\RegulatedOrganization')
            <x-regulated-organization-card level="3" :regulated-organization="$result" />
            <form action="{{ localized_route('regulated-organizations.join', $result) }}" method="POST">
                @csrf
                <button>{{ __('Request to join') }}</button>
            </form>
            @endif
        </div>
    @empty
        @if($query)
            <p>{{ __('No results found.') }}</p>
        @endif
    @endforelse
</div>

