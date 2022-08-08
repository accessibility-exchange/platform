<fieldset id="{{ $name }}" class="date field">
    <legend>
        {{ $label ?? __('forms.label_date') }}
    </legend>

    @if($hint)
        <x-hearth-hint :for="$name">{{ $hint }}</x-hearth-hint>
    @endif

    <div class="field field--year stack @error($name) field--error @enderror">
        <x-hearth-label :for="$name . '_year'" :value="__('forms.label_year')" />
        <x-hearth-input type="number" :name="$name . '_year'" :min="$minimumYear" pattern="[0-9]*" inputmode="numeric" :required="$required" :disabled="$disabled" :aria-describedby="$name.'-hint'" wire:model="year" />
    </div>

    <div class="field field--month stack @error($name) field--error @enderror">
        <x-hearth-label :for="$name . '_month'" :value="__('forms.label_month')" />
        <x-hearth-select :name="$name . '_month'" :options="$months" :required="$required" :disabled="$disabled" :aria-describedby="$name.'-hint'" wire:model="month" />
    </div>

    <div class="field field--day stack @error($name) field--error @enderror">
        <x-hearth-label :for="$name . '_day'" :value="__('forms.label_day')" />
        <x-hearth-input type="number" :name="$name . '_day'" min="1" max="31" pattern="[0-9]*" inputmode="numeric" :required="$required" :disabled="$disabled" :aria-describedby="$name.'-hint'" wire:model="day" />
    </div>

    <x-hearth-error :for="$name" />

    <x-hearth-input type="hidden" :name="$name" :value="$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($day, 2, '0', STR_PAD_LEFT)" />
</fieldset>
