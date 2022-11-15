@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'hint' => false,
    'required' => false,
    'disabled' => false,
])

<fieldset class="field @error($name) field--error @enderror" id="{{ $name }}"
    aria-describedby="{{ $name }}-hint {{ $name }}-output" x-data="dateInput('{{ $value }}')">
    <legend>
        {{ $label ?? __('forms.label_date') }}
    </legend>

    @if ($hint)
        <x-hearth-hint :for="$name">{{ $hint }}</x-hearth-hint>
    @endif
    <div class="flex w-full flex-wrap gap-4 md:flex-nowrap">
        <div class="field stack @error($name) field--error @enderror">
            <x-hearth-label :for="$name . '_year'" :value="__('forms.label_year')" />
            <x-hearth-input class="w-20" type="text" :name="$name . '_year'" inputmode="numeric" :required="$required"
                :disabled="$disabled" :aria-describedby="$name . '-hint'" x-mask="9999" x-model="year" />
        </div>
        <div class="field stack @error($name) field--error @enderror">
            <x-hearth-label :for="$name . '_month'" :value="__('forms.label_month')" />
            <x-hearth-select class="w-auto" :name="$name . '_month'" :options="[
                ['value' => '', 'label' => __('Choose a month…')],
                ['value' => '01', 'label' => __('forms.months.1')],
                ['value' => '02', 'label' => __('forms.months.2')],
                ['value' => '03', 'label' => __('forms.months.3')],
                ['value' => '04', 'label' => __('forms.months.4')],
                ['value' => '05', 'label' => __('forms.months.5')],
                ['value' => '06', 'label' => __('forms.months.6')],
                ['value' => '07', 'label' => __('forms.months.7')],
                ['value' => '08', 'label' => __('forms.months.8')],
                ['value' => '09', 'label' => __('forms.months.9')],
                ['value' => '10', 'label' => __('forms.months.10')],
                ['value' => '11', 'label' => __('forms.months.11')],
                ['value' => '12', 'label' => __('forms.months.12')],
            ]" :required="$required" :disabled="$disabled"
                :aria-describedby="$name . '-hint'" x-model="month" />
        </div>
        <div class="field stack @error($name) field--error @enderror">
            <x-hearth-label :for="$name . '_day'" :value="__('forms.label_day')" />
            <x-hearth-input class="w-20" type="text" :name="$name . '_day'" inputmode="numeric" :required="$required"
                :disabled="$disabled" :aria-describedby="$name . '-hint'" x-mask="99" x-model="day" />
        </div>
    </div>
    <input name="{{ $name }}" type="hidden" x-bind:value="getDate" />
</fieldset>
