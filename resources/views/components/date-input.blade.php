<fieldset id="{{ $name }}" class="date" aria-describedby="{{ $name }}-hint {{ $name }}-output" x-data="dateInput()">
    <legend>
        {{ $label ?? __('forms.label_date') }}
    </legend>
    <p id="{{ $name }}-hint" class="hint">{{ __('forms.date_hint') }}</p>
    <label class="year">
        {{ __('forms.label_year') }}
        <input type="text" name="{{ $name }}_year" pattern="[0-9]*" inputmode="numeric" x-model="year" x-on:blur="updateDate()">
    </label>
    <label class="month">
        {{ __('forms.label_month') }}
        <input type="text" name="{{ $name }}_month" pattern="[0-9]*" inputmode="numeric" x-model="month" x-on:blur="updateDate()">
    </label>
    <label class="day">
        {{ __('forms.label_day') }}
        <input type="text" name="{{ $name }}_day" pattern="[0-9]*" inputmode="numeric" x-model="day" x-on:blur="updateDate()">
    </label>
    <input type="hidden" name="{{ $name }}" x-bind:value="date" />
    <output id="{{ $name }}-output" aria-live="assertive" x-text="output()"></output>
</fieldset>
