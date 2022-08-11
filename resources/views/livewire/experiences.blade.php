<div class="stack">
    <ul role="list" class="stack">
        @foreach($experiences as $i => $experience)
        <li class="stack">
            <div class="field @error("work_and_volunteer_experiences.{$i}.title") field--error @enderror">
                <x-hearth-label :for="$name . '_' . $i . '_title'" :value="__('Title of role')" />
                <x-hearth-input :id="$name . '_' . $i . '_title'" :name="$name . '[' . $i . '][title]'" :value="$experience['title'] ?? ''" />
                <x-hearth-error :for="$name . '_' . $i . '_title'" :field="$name . '.' . $i . '.title'" />
            </div>
            <div class="field @error("work_and_volunteer_experiences.{$i}.organization") field--error @enderror">
                <x-hearth-label :for="$name . '_' . $i . '_organization'" :value="__('Name of organization')" />
                <x-hearth-input :id="$name . '_' . $i . '_organization'" :name="$name . '[' . $i . '][organization]'" :value="$experience['organization'] ?? ''" />
                <x-hearth-error :for="$name . '_' . $i . '_organization'" :field="$name . '.' . $i . '.organization'" />
            </div>
            <div class="field @error("work_and_volunteer_experiences.{$i}.start_year") field--error @enderror">
                <x-hearth-label :for="$name . '_start_year_' . $i" :value="__('Start year')" />
                <x-hearth-input type="number" pattern="[0-9]*" inputmode="numeric" min="1900" :max="date('Y')" :id="$name . '_' . $i . '_start_year'" :name="$name . '[' . $i . '][start_year]'" :value="$experience['start_year']" />
                <x-hearth-error :for="$name . '_' . $i . '_start_year'" :field="$name . '.' . $i . '.start_year'" />
            </div>
            <div class="field @error("work_and_volunteer_experiences.{$i}.end_year") field--error @enderror">
                <x-hearth-label :for="$name . '_end_year_' . $i" :value="__('End year')" />
                <x-hearth-input type="number" pattern="[0-9]*" inputmode="numeric" min="1900" :max="date('Y')" :id="$name . '_' . $i . '_end_year'" :name="$name . '[' . $i . '][end_year]'" :value="$experience['end_year'] ?? ''" />
                <x-hearth-error :for="$name . '_' . $i . '_end_year'" :field="$name . '.' . $i . '.end_year'" />
            </div>
            <div class="field">
                <x-hearth-checkbox :id="$name . '_' . $i . '_current'" :name="$name . '[' . $i . '][current]'" :checked="$experience['current'] ?? false" />
                <x-hearth-label :for="$name . '_' . $i . '_current'" :value="__('I currently work or volunteer here')" />
            </div>
            @if($loop->count > 1)
            <button class="secondary" type="button" wire:click="removeExperience({{ $i }})">{{ __('Remove experience') }}</button>
            @endif
        </li>
        @endforeach
    </ul>
    @if ($this->canAddMoreExperiences())
    <button class="secondary" type="button" wire:click="addExperience">{{ __('Add experience') }}</button>
    @endif
</div>
