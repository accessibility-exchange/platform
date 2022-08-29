@error($field, $bag)
    <p class="field__error" id="{{ $for }}-error">
        <x-heroicon-o-x-circle aria-hidden="true" /> {{ $message }}
    </p>
@enderror
