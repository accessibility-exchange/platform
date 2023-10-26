<x-slot name="title">
    {{ __('Projects') }}
</x-slot>

<x-slot name="header">
    <h1 id="browse-all-projects">
        {{ __('Browse all projects') }}
    </h1>
    <x-interpretation name="{{ __('Browse all projects', [], 'en') }}" namespace="all_projects" />
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
                    __('{1} :count result for “:searchQuery”.', ['count' => $projects->total(), 'searchQuery' => $searchQuery]) .
                        '|' .
                        __(':count results for “:searchQuery”.', ['count' => $projects->total(), 'searchQuery' => $searchQuery]),
                    $projects->total(),
                ) }}
            </p>
        @elseif (
            $statuses ||
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
                    __('{1} :count project matches your applied filters.', ['count' => $projects->total()]) .
                        '|' .
                        __(':count projects match your applied filters.', ['count' => $projects->total()]),
                    $projects->total(),
                ) }}
            </p>
        @endif
    </div>

    <div class="stack with-sidebar with-sidebar:2/3">
        <div class="filters">
            <h2 class="mb-6 mt-0">{{ __('Filters') }}</h2>
            <x-interpretation name="{{ __('Filters', [], 'en') }}" namespace="all_projects" />
            <div class="mb-6">
                <button class="secondary" type="button" wire:click="selectNone()">{{ __('Clear filters') }}</button>
            </div>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Status') }}</x-slot>
                <x-interpretation name="{{ __('Status', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Status') }}</legend>
                    @foreach ($statusesData as $status)
                        <div class="field">
                            <x-hearth-input id="status-{{ $status['value'] }}" name="statuses[]" type="checkbox"
                                value="{{ $status['value'] }}" wire:model.live="statuses" />
                            <label for="status-{{ $status['value'] }}">{{ $status['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Who they’re seeking') }}</x-slot>
                <x-interpretation name="{{ __('Who they’re seeking', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('seeking') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Who they’re seeking') }}</legend>
                    @foreach ($seekingsData as $seeking)
                        <div class="field">
                            <x-hearth-input id="seeking-{{ $seeking['value'] }}" name="seekings[]" type="checkbox"
                                value="{{ $seeking['value'] }}" wire:model.live="seekings" />
                            <label for="seeking-{{ $seeking['value'] }}">{{ $seeking['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="seeking" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Initiated by') }}</x-slot>
                <x-interpretation name="{{ __('Initiated by', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('initiator') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Initiated by') }}</legend>
                    @foreach ($initiatorsData as $initiator)
                        <div class="field">
                            <x-hearth-input id="initiator-{{ $initiator['value'] }}" name="initiators[]"
                                type="checkbox" value="{{ $initiator['value'] }}" wire:model.live="initiators" />
                            <label for="initiator-{{ $initiator['value'] }}">{{ $initiator['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="initiator" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Disability and Deaf groups they are looking for') }}</x-slot>
                <x-interpretation name="{{ __('Disability and Deaf groups they are looking for', [], 'en') }}"
                    namespace="all_projects" />
                <fieldset class="filter__options field @error('seekingGroup') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Disability and Deaf groups they are looking for') }}
                    </legend>
                    @foreach ($seekingGroupsData as $seekingGroup)
                        <div class="field">
                            <x-hearth-input id="seekingGroup-{{ $seekingGroup['value'] }}" name="seekingGroups[]"
                                type="checkbox" value="{{ $seekingGroup['value'] }}" wire:model.live="seekingGroups" />
                            <label
                                for="seekingGroup-{{ $seekingGroup['value'] }}">{{ $seekingGroup['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="seekingGroup" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Virtual or in-person') }}</x-slot>
                <x-interpretation name="{{ __('Virtual or in-person', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('meetingType') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Virtual or in-person') }}</legend>
                    @foreach ($meetingTypesData as $meetingType)
                        <div class="field">
                            <x-hearth-input id="meetingType-{{ $meetingType['value'] }}" name="meetingTypes[]"
                                type="checkbox" value="{{ $meetingType['value'] }}" wire:model.live="meetingTypes" />
                            <label for="meetingType-{{ $meetingType['value'] }}">{{ $meetingType['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="meetingType" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Participant location') }}</x-slot>
                <x-interpretation name="{{ __('Participant location', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('location') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Participant location') }}</legend>
                    @foreach ($locationsData as $location)
                        <div class="field">
                            <x-hearth-input id="location-{{ $location['value'] }}" name="locations[]" type="checkbox"
                                value="{{ $location['value'] }}" wire:model.live="locations" />
                            <label for="location-{{ $location['value'] }}">{{ $location['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="location" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Compensation') }}</x-slot>
                <x-interpretation name="{{ __('Compensation', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('compensation') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Compensation') }}</legend>
                    @foreach ($compensationsData as $compensation)
                        <div class="field">
                            <x-hearth-input id="compensation-{{ $compensation['value'] }}" name="compensations[]"
                                type="checkbox" value="{{ $compensation['value'] }}" wire:model.live="compensations" />
                            <label
                                for="compensation-{{ $compensation['value'] }}">{{ $compensation['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="compensation" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Sectors') }}</x-slot>
                <x-interpretation name="{{ __('Sectors', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('sector') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Sectors') }}</legend>
                    @foreach ($sectorsData as $sector)
                        <div class="field">
                            <x-hearth-input id="sector-{{ $sector['value'] }}" name="sectors[]" type="checkbox"
                                value="{{ $sector['value'] }}" wire:model.live="sectors" />
                            <label for="sector-{{ $sector['value'] }}">{{ $sector['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="sector" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Areas of impact') }}</x-slot>
                <x-interpretation name="{{ __('Areas of impact', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('impact') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Areas of impact') }}</legend>
                    @foreach ($impactedAreasData as $impact)
                        <div class="field">
                            <x-hearth-input id="impact-{{ $impact['value'] }}" name="impacts[]" type="checkbox"
                                value="{{ $impact['value'] }}" wire:model.live="impacts" />
                            <label for="impact-{{ $impact['value'] }}">{{ $impact['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="impact" />
                </fieldset>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Recruitment method') }}</x-slot>
                <x-interpretation name="{{ __('Recruitment method', [], 'en') }}" namespace="all_projects" />
                <fieldset class="filter__options field @error('recruitment') field--error @enderror">
                    <legend class="visually-hidden">{{ __('Recruitment method') }}</legend>
                    @foreach ($recruitmentMethodsData as $recruitmentMethod)
                        <div class="field">
                            <x-hearth-input id="recruitmentMethod-{{ $recruitmentMethod['value'] }}"
                                name="recruitmentMethods[]" type="checkbox"
                                value="{{ $recruitmentMethod['value'] }}" wire:model.live="recruitmentMethods" />
                            <label
                                for="recruitmentMethod-{{ $recruitmentMethod['value'] }}">{{ $recruitmentMethod['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="recruitment" />
                </fieldset>
            </x-expander>
        </div>
        <div class="md:pl-4">
            <section aria-labelledby="browse-all-projects">
                <div class="projects stack">
                    @forelse($projects as $project)
                        <x-card.project :model="$project" :level="2" />
                    @empty
                        <p>{{ __('No projects found.') }}</p>
                    @endforelse
                </div>
            </section>

            {{ $projects->onEachSide(2)->links('vendor.livewire.tailwind-custom') }}
        </div>
    </div>
</div>
