@error($field, $bag)
<p class="field__error" id="{{ $for }}-error">
    <x-heroicon-s-exclamation-circle height="24" width="24" aria-hidden="true" /> {{ $message }}
</p>
@enderror
