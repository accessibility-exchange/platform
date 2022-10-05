<x-slot name="title">
    {{ __('Projects') }}
</x-slot>

<x-slot name="header">
    <h1 id="browse-all-projects">
        {{ __('Browse all projects') }}
    </h1>
</x-slot>

<div>
    <div role="alert" x-data="{ visible: false }" @add-flash-message.window="visible = true"
        @clear-flash-message.window="visible = false"
        @remove-flash-message.window="setTimeout(() => visible = false, 5000)">
        <div x-show="visible" x-transition:leave.duration.500ms>
            @if (session()->has('message'))
                <x-hearth-alert type="success">
                    {!! Str::markdown(session('message')) !!}
                </x-hearth-alert>
            @endif
        </div>
    </div>

    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="query" :value="__('Search')" />
        <div class="repel">
            <x-hearth-input name="query" type="search" wire:model.defer="query" wire:search="search" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div role="alert">
        @if ($query)
            <p class="h4">
                {{ __(':count results for “:query”', ['count' => $projects->total(), 'query' => $query]) }}
            </p>
        @endif
    </div>

    <div class="browser__all__projects__content">
        <div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('status') field--error @enderror">
                    <legend>{{ __('Status') }}</legend>
                    @foreach ($statusesData as $status)
                        <div>
                            <input type="checkbox" value="{{ $status['value'] }}"
                                wire:model="statuses.{{ $status['value'] }}">
                            <label>{{ $status['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="status" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('seeking') field--error @enderror">
                    <legend>{{ __("Who they're seeking") }}</legend>
                    @foreach ($seekingsData as $seeking)
                        <div>
                            <input type="checkbox" value="{{ $seeking['value'] }}"
                                wire:model="seekings.{{ $seeking['value'] }}">
                            <label>{{ $seeking['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="seeking" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('initiator') field--error @enderror">
                    <legend>{{ __('Initiated by') }}</legend>
                    @foreach ($initiatorsData as $initiator)
                        <div>
                            <input type="checkbox" value="{{ $initiator['value'] }}"
                                wire:model="initiators.{{ $initiator['value'] }}">
                            <label>{{ $initiator['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="initiator" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('seekingGroup') field--error @enderror">
                    <legend>{{ __('Disability and Deaf groups they are looking for') }}</legend>
                    @foreach ($seekingGroupsData as $seekingGroup)
                        <div>
                            <input type="checkbox" value="{{ $seekingGroup['value'] }}"
                                wire:model="seekingGroups.{{ $seekingGroup['value'] }}">
                            <label>{{ $seekingGroup['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="seekingGroup" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('meetingType') field--error @enderror">
                    <legend>{{ __('Virtual or in-person') }}</legend>
                    @foreach ($meetingTypesData as $meetingType)
                        <div>
                            <input type="checkbox" value="{{ $meetingType['value'] }}"
                                wire:model="meetingTypes.{{ $meetingType['value'] }}">
                            <label>{{ $meetingType['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="meetingType" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('location') field--error @enderror">
                    <legend>{{ __('Participant location') }}</legend>
                    @foreach ($locationsData as $location)
                        <div>
                            <input type="checkbox" value="{{ $location['value'] }}"
                                wire:model="locations.{{ $location['value'] }}">
                            <label>{{ $location['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="location" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('compensation') field--error @enderror">
                    <legend>{{ __('Compensation') }}</legend>
                    @foreach ($compensationsData as $compensation)
                        <div>
                            <input type="checkbox" value="{{ $compensation['value'] }}"
                                wire:model="compensations.{{ $compensation['value'] }}">
                            <label>{{ $compensation['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="compensation" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('sector') field--error @enderror">
                    <legend>{{ __('Sectors') }}</legend>
                    @foreach ($sectorsData as $sector)
                        <div>
                            <input type="checkbox" value="{{ $sector['value'] }}"
                                wire:model="sectors.{{ $sector['value'] }}">
                            <label>{{ $sector['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="sector" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('impact') field--error @enderror">
                    <legend>{{ __('Areas of impact') }}</legend>
                    @foreach ($impactedAreasData as $impact)
                        <div>
                            <input type="checkbox" value="{{ $impact['value'] }}"
                                wire:model="impacts.{{ $impact['value'] }}">
                            <label>{{ $impact['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="impact" />
                </fieldset>
            </div>
            <div class="filter__options__container">
                <fieldset class="filter__options field @error('recruitment') field--error @enderror">
                    <legend>{{ __('Recruitment method') }}</legend>
                    @foreach ($recruitmentMethodsData as $recruitmentMethod)
                        <div>
                            <input type="checkbox" value="{{ $recruitmentMethod['value'] }}"
                                wire:model="recruitmentMethods.{{ $recruitmentMethod['value'] }}">
                            <label>{{ $recruitmentMethod['label'] }}</label>
                        </div>
                    @endforeach
                    <x-hearth-error for="recruitment" />
                </fieldset>
            </div>
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
