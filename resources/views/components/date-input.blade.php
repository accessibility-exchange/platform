<fieldset class="date" id="{{ $name }}" aria-describedby="{{ $name }}-hint {{ $name }}-output"
    x-data="dateInput()" x-init="dateToComponents('{{ $value }}')">
    <legend>
        {{ $label ?? __('forms.label_date') }}
    </legend>
    <p class="hint" id="{{ $name }}-hint">{{ __('forms.date_hint') }}</p>
    <label class="year">
        {{ __('forms.label_year') }}
        <input name="{{ $name }}_year" type="text" pattern="[0-9]*" inputmode="numeric" x-model="year"
            x-on:blur="componentsToDate()">
    </label>
    <label class="month">
        {{ __('forms.label_month') }}
        <input name="{{ $name }}_month" type="text" pattern="[0-9]*" inputmode="numeric" x-model="month"
            x-on:blur="componentsToDate()">
    </label>
    <label class="day">
        {{ __('forms.label_day') }}
        <input name="{{ $name }}_day" type="text" pattern="[0-9]*" inputmode="numeric" x-model="day"
            x-on:blur="componentsToDate()">
    </label>
    <input name="{{ $name }}" type="hidden" x-model="date" />
    <output id="{{ $name }}-output" aria-live="assertive" x-text="output()"></output>
</fieldset>
