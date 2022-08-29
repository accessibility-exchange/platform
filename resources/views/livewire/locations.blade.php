<div class="stack">
    @if ($locations)
        <ul class="stack" role="list">
            @foreach ($locations as $i => $location)
                <li class="stack">
                    <fieldset>
                        <legend>{{ __('Location') }}</legend>
                        <div class="field @error("{$name}.{$i}.region") field--error @enderror">
                            <x-hearth-label :for="$name . '_' . $i . '_region'" :value="__('Province or territory (required)')" />
                            <x-hearth-select :id="$name . '_' . $i . '_region'" :name="$name . '[' . $i . '][region]'" :options="$regions" :selected="$location['region'] ?? ''"
                                required />
                            <x-hearth-error :for="$name . '_' . $i . '_region'" :field="$name . '.' . $i . '.region'" />
                        </div>

                        <div class="field @error("{$name}.{$i}.locality") field-error @enderror">
                            <x-hearth-label :for="$name . '_' . $i . '_locality'" :value="__('City or town (required)')" />
                            <x-hearth-input :id="$name . '_' . $i . '_locality'" :name="$name . '[' . $i . '][locality]'" :value="$location['locality'] ?? ''" required />
                            <x-hearth-error :for="$name . '_' . $i . '_locality'" :field="$name . '.' . $i . '.locality'" />
                        </div>
                    </fieldset>

                    <button class="secondary" type="button"
                        wire:click="removeLocation({{ $i }})">{{ __('Remove this location') }}</button>
                </li>
            @endforeach
        </ul>
    @endif
    @if ($this->canAddMoreLocations())
        <button class="secondary" type="button" wire:click="addLocation">{{ __('Add a location') }}</button>
    @endif
</div>
