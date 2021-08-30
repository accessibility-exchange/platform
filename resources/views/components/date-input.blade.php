<fieldset id="{{ $name }}" class="date" aria-describedby="{{ $name }}-format-hint @if(isset($hint)) {{ $name }}-hint @endif {{ $name }}-output" x-data="dateInput()" x-init="dateToComponents('{{ $value }}')">
    <legend>
        {{ $label ?? __('forms.label_date') }}
    </legend>
    <p id="{{ $name }}-format-hint" class="hint">{{ __('forms.date_hint') }}</p>
    <label class="year">
        {{ __('forms.label_year') }}
        <input type="text" name="{{ $name }}_year" pattern="[0-9]*" inputmode="numeric" x-model="year" x-on:blur="componentsToDate()">
    </label>
    <label class="month">
        {{ __('forms.label_month') }}
        <input type="text" name="{{ $name }}_month" pattern="[0-9]*" inputmode="numeric" x-model="month" x-on:blur="componentsToDate()">
    </label>
    <label class="day">
        {{ __('forms.label_day') }}
        <input type="text" name="{{ $name }}_day" pattern="[0-9]*" inputmode="numeric" x-model="day" x-on:blur="componentsToDate()">
    </label>
    <input type="hidden" name="{{ $name }}" x-model="date" />
    <output id="{{ $name }}-output" aria-live="assertive" x-text="output()"></output>
    @if(isset($hint))
    <p class="field__hint" id="{{ $name }}-hint">{{ $hint }}</p>
    @endif
</fieldset>
