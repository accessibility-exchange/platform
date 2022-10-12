<fieldset class="field @error($name) field--error @enderror" id="{{ $name }}">
    <legend>
        {{ $label ?? __('forms.label_date') }}
    </legend>

    @if ($hint)
        <x-hearth-hint :for="$name">{{ $hint }}</x-hearth-hint>
    @endif

    <div class="flex w-full flex-wrap gap-4 md:flex-nowrap">
        <div class="field stack @error($name) field--error @enderror">
            <x-hearth-label :for="$name . '_year'" :value="__('forms.label_year')" />
            <x-hearth-input class="w-20" type="number" :name="$name . '_year'" :min="$minimumYear ?? $maximumYear - 25" :max="$maximumYear ?? $minimumYear + 25"
                pattern="[0-9]*" inputmode="numeric" :required="$required" :disabled="$disabled" :aria-describedby="$name . '-hint'"
                :value="old($name . '_year')" wire:model.lazy="year" />
        </div>

        <div class="field stack @error($name) field--error @enderror">
            <x-hearth-label :for="$name . '_month'" :value="__('forms.label_month')" />
            <x-hearth-select class="w-auto" :name="$name . '_month'" :options="$months" :required="$required" :disabled="$disabled"
                :aria-describedby="$name . '-hint'" :value="old($name . '_month')" wire:model="month" />
        </div>

        <div class="field stack @error($name) field--error @enderror">
            <x-hearth-label :for="$name . '_day'" :value="__('forms.label_day')" />
            <x-hearth-input class="w-20" type="number" :name="$name . '_day'" min="1" max="31"
                pattern="[0-9]*" inputmode="numeric" :required="$required" :disabled="$disabled" :aria-describedby="$name . '-hint'"
                :value="old($name . '_day')" wire:model.lazy="day" />
        </div>
    </div>

    <x-hearth-error :for="$name" />

    <x-hearth-input type="hidden" :name="$name" :value="$this->date" />
</fieldset>
