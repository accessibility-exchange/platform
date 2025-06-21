<x-slot name="title">
    {{ $library->title }}
</x-slot>

<x-slot name="header">
    <div class="center center:wide stack pb-12 pt-4">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('resources-and-training') }}">{{ __('Resources and training') }}</a></li>
            <li><a href="{{ localized_route('libraries.index') }}">{{ __('Libraries') }}</a></li>
        </ol>
        <p class="h4">{{ __('Library') }}</p>
        <h1 class="mt-0" id="library-title">
            {{ $library->title }}
        </h1>

        @if ($library->description)
            {!! Str::markdown($library->description, config('markdown')) !!}
        @endif
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
    <x-section class="full" aria-labelledby="resource-collections">
        <div class="center center:wide stack stack:xl">
            <h2 id="resource-collections">{{ __('Collections in this library') }}</h2>
            <x-interpretation name="{{ __('Collections in this library', [], 'en') }}" />
            @if ($resourceCollections?->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($resourceCollections->take(10) as $resourceCollection)
                        <x-card.resource-collection :model="$resourceCollection" />
                    @endforeach
                </div>
            @else
                <p>{{ __('resource-collection.none_found') }}</p>
            @endif
        </div>
    </x-section>

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
                    __('{1} :count resource matches your applied filters.', ['count' => $resources->total()]) .
                        '|' .
                        __(':count resources match your applied filters.', ['count' => $resources->total()]),
                    $resources->total(),
                ) }}
            </p>
        @endif
    </div>

    <div class="full accent">
        <div class="center center:wide stack stack:xl">
            <h2 id="resource-collections">{{ __('Resources in this library') }}</h2>
            <x-interpretation name="{{ __('Resources in this library', [], 'en') }}" />
        </div>
        <div class="center center:wide stack with-sidebar with-sidebar:2/3">
            <div class="filters">
                <h2 class="visually-hidden">{{ __('Filters') }}</h2>
                <x-expander :level="3">
                    <x-slot name="summary">{{ __('Resource type') }}</x-slot>
                    <fieldset class="filter__options field @error('status') field--error @enderror">
                        <legend class="visually-hidden">{{ __('Resource type') }}</legend>
                        @foreach ($contentTypesData as $contentType)
                            <div class="field">
                                <x-hearth-input id="contentType-{{ $contentType['value'] }}" name="contentTypes[]"
                                    type="checkbox" value="{{ $contentType['value'] }}"
                                    wire:model.live="contentTypes" />
                                <label
                                    for="contentType-{{ $contentType['value'] }}">{{ $contentType['label'] }}</label>
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
                                <x-hearth-input id="language-{{ $language['value'] }}" name="languages[]"
                                    type="checkbox" value="{{ $language['value'] }}" wire:model.live="languages" />
                                <label for="language-{{ $language['value'] }}">{{ $language['label'] }}</label>
                            </div>
                        @endforeach
                        <x-hearth-error for="status" />
                    </fieldset>
                </x-expander>
                <div class="mt-6">
                    <button class="secondary" type="button"
                        wire:click="selectNone()">{{ __('Select none') }}</button>
                </div>
            </div>
            <div class="md:pl-4">
                <section aria-labelledby="collection-title">
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
    @can('update', $library)
        <p class="mt-12"><a class="cta secondary"
                href="{{ route('filament.admin.resources.libraries.edit', $library) }}">@svg('heroicon-o-pencil', 'mr-1')
                {{ __('Edit library') }}</a></p>
    @endcan
</div>
