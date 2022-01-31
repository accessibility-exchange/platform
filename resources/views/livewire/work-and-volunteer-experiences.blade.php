<div class="flow">
    <ul role="list" class="flow">
        @foreach($experiences as $i => $experience)
        <li class="flow">
            <div class="field @error("work_and_volunteer_experiences.{$i}.title") field--error @enderror">
                <x-hearth-label :for="'work_and_volunteer_experiences_' . $i . '_title'" :value="__('Title of role')" />
                <x-hearth-input :id="'work_and_volunteer_experiences_' . $i . '_title'" :name="'work_and_volunteer_experiences[' . $i . '][title]'" :value="$experience['title']" />
                <x-hearth-error :for="'work_and_volunteer_experiences_' . $i . '_title'" :field="'work_and_volunteer_experiences.' . $i . '.title'" />
            </div>
            <div class="field @error("work_and_volunteer_experiences.{$i}.start_year") field--error @enderror">
                <x-hearth-label :for="'work_and_volunteer_experiences_start_year_' . $i" :value="__('Start year')" />
                <x-hearth-input type="number" :id="'work_and_volunteer_experiences_' . $i . '_start_year'" :name="'work_and_volunteer_experiences[' . $i . '][start_year]'" :value="$experience['start_year']" />
                <x-hearth-error :for="'work_and_volunteer_experiences_' . $i . '_start_year'" :field="'work_and_volunteer_experiences.' . $i . '.start_year'" />
            </div>
            <div class="field @error("work_and_volunteer_experiences.{$i}.end_year") field--error @enderror">
                <x-hearth-label :for="'work_and_volunteer_experiences_end_year_' . $i" :value="__('End year')" />
                <x-hearth-input type="number" :id="'work_and_volunteer_experiences_' . $i . '_end_year'" :name="'work_and_volunteer_experiences[' . $i . '][end_year]'" :value="$experience['end_year'] ?? ''" />
                <x-hearth-error :for="'work_and_volunteer_experiences_' . $i . '_end_year'" :field="'work_and_volunteer_experiences.' . $i . '.end_year'" />
            </div>
            <div class="field">
                <x-hearth-checkbox :id="'work_and_volunteer_experiences_' . $i . '_current'" :name="'work_and_volunteer_experiences[' . $i . '][current]'" :checked="$experience['current'] ?? false" />
                <x-hearth-label :for="'work_and_volunteer_experiences_' . $i . '_current'" :value="__('I currently work here')" />
            </div>
            <button type="button" wire:click="removeExperience({{ $i }})">{{ __('Remove this experience') }}</button>
        </li>
        @endforeach
    </ul>
    @if ($this->canAddMoreExperiences())
    <button type="button" wire:click="addExperience">{{ __('Add another experience') }}</button>
    @endif
</div>
