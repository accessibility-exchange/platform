<x-slot name="title">
    {{ __('Projects') }}
</x-slot>

<x-slot name="header">
    <h1 id="browse-all-projects">
        {{ __('Browse all projects') }}
    </h1>
</x-slot>

<div>
    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="searchQuery" :value="__('Search')" />
        <div class="repel my-0">
            <x-hearth-input name="searchQuery" type="search" wire:model.defer="searchQuery" wire:search="search" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div class="search search-and-filter-results" role="alert">
        @if ($searchQuery)
            <p class="h4">
                {{ trans_choice(
                    __('{1} :count result for ":searchQuery".', ['count' => $projects->total(), 'searchQuery' => $searchQuery]) .
                        '|' .
                        __(':count results for ":searchQuery".', ['count' => $projects->total(), 'searchQuery' => $searchQuery]),
                    $projects->total(),
                ) }}
            </p>
        @elseif ($statuses ||
            $seekings ||
            $seekingGroups ||
            $initiators ||
            $meetingTypes ||
            $locations ||
            $compensations ||
            $sectors ||
            $impacts ||
            $recruitmentMethods)
            <p class="h4">
                {{ trans_choice(
                    __('{1} :count project matches your applied filters', ['count' => $projects->total()]) .
                        '|' .
                        __(':count projects match your applied filters', ['count' => $projects->total()]),
                    $projects->total(),
                ) }}
            </p>
        @endif
    </div>

    <div class="stack with-sidebar with-sidebar:2/3">
        <div class="filters">
            <h2 class="visually-hidden">{{ __('Filters') }}</h2>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <x-slot name="summary">{{ __('Status') }}</x-slot>
                    <ul role="list">
                        @foreach ($statusesData as $status)
                            <li>
                                <x-hearth-input id="status-{{ $status['value'] }}" name="statuses[]" type="checkbox"
                                    value="{{ $status['value'] }}" wire:model="statuses.{{ $status['value'] }}" />
                                <label for="status-{{ $status['value'] }}">{{ $status['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('seeking') field--error @enderror">
                    <x-slot name="summary">{{ __("Who they're seeking") }}</x-slot>
                    <ul role="list">
                        @foreach ($seekingsData as $seeking)
                            <li>
                                <x-hearth-input id="seeking-{{ $seeking['value'] }}" name="seekings[]" type="checkbox"
                                    value="{{ $seeking['value'] }}" wire:model="seekings.{{ $seeking['value'] }}" />
                                <label for="seeking-{{ $seeking['value'] }}">{{ $seeking['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="seeking" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('initiator') field--error @enderror">
                    <x-slot name="summary">{{ __('Initiated by') }}</x-slot>
                    <ul role="list">
                        @foreach ($initiatorsData as $initiator)
                            <li>
                                <x-hearth-input id="initiator-{{ $initiator['value'] }}" name="initiators[]"
                                    type="checkbox" value="{{ $initiator['value'] }}"
                                    wire:model="initiators.{{ $initiator['value'] }}" />
                                <label for="initiator-{{ $initiator['value'] }}">{{ $initiator['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="initiator" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('seekingGroup') field--error @enderror">
                    <x-slot name="summary">{{ __('Disability and Deaf groups they are looking for') }}</x-slot>
                    <ul role="list">
                        @foreach ($seekingGroupsData as $seekingGroup)
                            <li>
                                <x-hearth-input id="seekingGroup-{{ $seekingGroup['value'] }}" name="seekingGroups[]"
                                    type="checkbox" value="{{ $seekingGroup['value'] }}"
                                    wire:model="seekingGroups.{{ $seekingGroup['value'] }}" />
                                <label
                                    for="seekingGroup-{{ $seekingGroup['value'] }}">{{ $seekingGroup['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="seekingGroup" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('meetingType') field--error @enderror">
                    <x-slot name="summary">{{ __('Virtual or in-person') }}</x-slot>
                    <ul role="list">
                        @foreach ($meetingTypesData as $meetingType)
                            <li>
                                <x-hearth-input id="meetingType-{{ $meetingType['value'] }}" name="meetingTypes[]"
                                    type="checkbox" value="{{ $meetingType['value'] }}"
                                    wire:model="meetingTypes.{{ $meetingType['value'] }}" />
                                <label
                                    for="meetingType-{{ $meetingType['value'] }}">{{ $meetingType['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="meetingType" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('location') field--error @enderror">
                    <x-slot name="summary">{{ __('Participant location') }}</x-slot>
                    <ul role="list">
                        @foreach ($locationsData as $location)
                            <li>
                                <x-hearth-input id="location-{{ $location['value'] }}" name="locations[]"
                                    type="checkbox" value="{{ $location['value'] }}"
                                    wire:model="locations.{{ $location['value'] }}" />
                                <label for="location-{{ $location['value'] }}">{{ $location['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="location" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('compensation') field--error @enderror">
                    <x-slot name="summary">{{ __('Compensation') }}</x-slot>
                    <ul role="list">
                        @foreach ($compensationsData as $compensation)
                            <li>
                                <x-hearth-input id="compensation-{{ $compensation['value'] }}" name="compensations[]"
                                    type="checkbox" value="{{ $compensation['value'] }}"
                                    wire:model="compensations.{{ $compensation['value'] }}" />
                                <label
                                    for="compensation-{{ $compensation['value'] }}">{{ $compensation['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="compensation" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('sector') field--error @enderror">
                    <x-slot name="summary">{{ __('Sectors') }}</x-slot>
                    <ul role="list">
                        @foreach ($sectorsData as $sector)
                            <li>
                                <x-hearth-input id="sector-{{ $sector['value'] }}" name="sectors[]" type="checkbox"
                                    value="{{ $sector['value'] }}" wire:model="sectors.{{ $sector['value'] }}" />
                                <label for="sector-{{ $sector['value'] }}">{{ $sector['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="sector" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('impact') field--error @enderror">
                    <x-slot name="summary">{{ __('Areas of impact') }}</x-slot>
                    <ul role="list">
                        @foreach ($impactedAreasData as $impact)
                            <li>
                                <x-hearth-input id="impact-{{ $impact['value'] }}" name="impacts[]" type="checkbox"
                                    value="{{ $impact['value'] }}" wire:model="impacts.{{ $impact['value'] }}" />
                                <label for="impact-{{ $impact['value'] }}">{{ $impact['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="impact" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <fieldset class="filter__options field @error('recruitment') field--error @enderror">
                    <x-slot name="summary">{{ __('Recruitment method') }}</x-slot>
                    <ul role="list">
                        @foreach ($recruitmentMethodsData as $recruitmentMethod)
                            <li>
                                <x-hearth-input id="recruitmentMethod-{{ $recruitmentMethod['value'] }}"
                                    name="recruitmentMethods[]" type="checkbox"
                                    value="{{ $recruitmentMethod['value'] }}"
                                    wire:model="recruitmentMethods.{{ $recruitmentMethod['value'] }}" />
                                <label
                                    for="recruitmentMethod-{{ $recruitmentMethod['value'] }}">{{ $recruitmentMethod['label'] }}</label>
                            </li>
                        @endforeach
                    </ul>
                    <x-hearth-error for="recruitment" />
                </fieldset>
            </x-expander>
            <button class="secondary" type="button" wire:click="selectNone()">{{ __('Select none') }}</button>
        </div>
        <div>
            <div role="region" aria-labelledby="browse-all-projects" tabindex="0">
                <div class="projects stack">
                    @forelse($projects as $project)
                        <x-card.project :project="$project" :level="2" />
                    @empty
                        <p>{{ __('No projects found.') }}</p>
                    @endforelse
                </div>
            </div>

            {{ $projects->onEachSide(2)->links('vendor.livewire.tailwind') }}
        </div>
    </div>
</div>
