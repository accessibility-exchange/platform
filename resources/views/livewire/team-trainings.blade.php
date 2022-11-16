<div class="stack">
    @if ($trainings)
        <ul class="stack" role="list">
            @foreach ($trainings as $i => $training)
                <li class="stack box">
                    <h4>{{ __('Training') }}</h4>
                    <div class="field @error("team_trainings.{$i}.name") field-error @enderror">
                        <x-hearth-label :for="'team_trainings_' . $i . '_name'" :value="__('Name of training')" />
                        <x-hearth-input :id="'team_trainings_' . $i . '_name'" :name="'team_trainings[' . $i . '][name]'" :value="$training['name'] ?? ''" required />
                        <x-hearth-error :for="'team_trainings_' . $i . '_name'" :field="'team_trainings.' . $i . '.name'" />
                    </div>

                    <div class="field @error("team_trainings.{$i}.date") field-error @enderror">
                        <x-date-picker :wire:key="'training-'.$i" :label="__('Date of training')" :name="'team_trainings[' . $i . '][date]'"
                            :value="old('team_trainings_' . $i . '_date', $training['date'] ?? '')" />
                        <x-hearth-error :for="'team_trainings_' . $i . '_date'" :field="'team_trainings.' . $i . '.date'" />
                    </div>

                    <fieldset>
                        <legend>
                            <h4>{{ __('Training organization or trainer') }}</h4>
                        </legend>
                        <div class="field @error("team_trainings.{$i}.trainer_name") field-error @enderror">
                            <x-hearth-label :for="'team_trainings_' . $i . '_trainer_name'" :value="__('Name')" />
                            <x-hearth-input :id="'team_trainings_' . $i . '_trainer_name'" :name="'team_trainings[' . $i . '][trainer_name]'" :value="$training['trainer_name'] ?? ''" required />
                            <x-hearth-error :for="'team_trainings_' . $i . '_trainer_name'" :field="'team_trainings.' . $i . '.trainer_name'" />
                        </div>

                        <div class="field @error("team_trainings.{$i}.trainer_url") field-error @enderror">
                            <x-hearth-label :for="'team_trainings_' . $i . '_trainer_url'" :value="__('Website')" />
                            <x-hearth-input :id="'team_trainings_' . $i . '_trainer_url'" :name="'team_trainings[' . $i . '][trainer_url]'" :value="$training['trainer_url'] ?? ''" required />
                            <x-hearth-error :for="'team_trainings_' . $i . '_trainer_url'" :field="'team_trainings.' . $i . '.trainer_url'" />
                        </div>
                    </fieldset>

                    <button class="secondary" type="button"
                        wire:click="removeTraining({{ $i }})">{{ __('Remove this training') }}</button>
                </li>
            @endforeach
        </ul>
    @endif
    @if ($this->canAddMoreTrainings())
        <button class="secondary" type="button" wire:click="addTraining">{{ __('Add a training') }}</button>
    @endif
</div>
