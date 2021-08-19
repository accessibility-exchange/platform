@error($for)
<p class="field__error" id="{{ $for }}-error">
    <x-heroicon-s-exclamation-circle style="display: inline-block; margin-right: 0.25em; margin-bottom: -0.125em; width: 1em; height: 1em;" /> {{ $message }}
</p>
@enderror
