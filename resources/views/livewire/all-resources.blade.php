<x-slot name="title">
    {{ __('Resources') }}
</x-slot>

<x-slot name="header">
    <div class="center center:wide stack pb-12 pt-4">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('resource-collections.index') }}">{{ __('Resources') }}</a></li>
        </ol>
        <h1 id="browse-all-resources">
            {{ __('Browse all resources') }}
        </h1>
    </div>
</x-slot>

<div>
    <form class="space-y-2" wire:submit="search">
        <x-hearth-label for="searchQuery" :value="__('Search')" />
        <div class="repel">
            <x-hearth-input name="searchQuery" type="search" wire:model="searchQuery" wire:search="search" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div class="search search-and-filter-results" role="alert">
        @if ($searchQuery)
            <p class="h4">
                {{ trans_choice(
                    __('{1} :count result for “:searchQuery”.', ['count' => $resources->total(), 'searchQuery' => $searchQuery]) .
                        '|' .
                        __(':count results for “:searchQuery”.', ['count' => $resources->total(), 'searchQuery' => $searchQuery]),
                    $resources->total(),
                ) }}
            </p>
        @elseif ($contentTypes || $impacts || $languages || $phases || $sectors || $topics)
            <p class="h4">
                {{ trans_choice(
                    __('{1} :count project matches your applied filters.', ['count' => $resources->total()]) .
                        '|' .
                        __(':count projects match your applied filters.', ['count' => $resources->total()]),
                    $resources->total(),
                ) }}
            </p>
        @endif
    </div>

    <div class="stack with-sidebar with-sidebar:2/3">
        <div class="filters">
            <h2 class="visually-hidden">{{ __('Filters') }}</h2>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Resource type') }}</x-slot>
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Resource type') }}</legend>
                    @foreach ($contentTypesData as $contentType)
                        <div class="field">
                            <x-hearth-input id="contentType-{{ $contentType['value'] }}" name="contentTypes[]"
                                type="checkbox" value="{{ $contentType['value'] }}" wire:model.live="contentTypes" />
                            <label for="contentType-{{ $contentType['value'] }}">{{ $contentType['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Topic') }}</x-slot>
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Topic') }}</legend>
                    @foreach ($topicsData as $topic)
                        <div class="field">
                            <x-hearth-input id="topic-{{ $topic['value'] }}" name="topics[]" type="checkbox"
                                value="{{ $topic['value'] }}" wire:model.live="topics" />
                            <label for="topic-{{ $topic['value'] }}">{{ $topic['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Phase of consultation') }}</x-slot>
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Phase of consultation') }}</legend>
                    @foreach ($phasesData as $phase)
                        <div class="field">
                            <x-hearth-input id="phase-{{ $phase['value'] }}" name="phases[]" type="checkbox"
                                value="{{ $phase['value'] }}" wire:model.live="phases" />
                            <label for="phase-{{ $phase['value'] }}">{{ $phase['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Sector') }}</x-slot>
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Sector') }}</legend>
                    @foreach ($sectorsData as $sector)
                        <div class="field">
                            <x-hearth-input id="sector-{{ $sector['value'] }}" name="sectors[]" type="checkbox"
                                value="{{ $sector['value'] }}" wire:model.live="sectors" />
                            <label for="sector-{{ $sector['value'] }}">{{ $sector['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Area of impact') }}</x-slot>
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Area of impact') }}</legend>
                    @foreach ($impactsData as $impact)
                        <div class="field">
                            <x-hearth-input id="impact-{{ $impact['value'] }}" name="impacts[]" type="checkbox"
                                value="{{ $impact['value'] }}" wire:model.live="impacts" />
                            <label for="impact-{{ $impact['value'] }}">{{ $impact['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Language') }}</x-slot>
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Language') }}</legend>
                    @foreach ($languagesData as $language)
                        <div class="field">
                            <x-hearth-input id="language-{{ $language['value'] }}" name="languages[]" type="checkbox"
                                value="{{ $language['value'] }}" wire:model.live="languages" />
                            <label for="language-{{ $language['value'] }}">{{ $language['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <div class="mt-6">
                <button class="secondary" type="button" wire:click="selectNone()">{{ __('Select none') }}</button>
            </div>
        </div>
        <div class="md:pl-4">
            <section aria-labelledby="browse-all-resources">
                <div class="resources stack">
                    @forelse($resources as $resource)
                        <x-card.resource :model="$resource" :level="2" />
                    @empty
                        <p>{{ __('No resources found.') }}</p>
                    @endforelse
                </div>
            </section>

            {{ $resources->onEachSide(2)->links('vendor.livewire.tailwind-custom') }}
        </div>
    </div>
</div>
