<textarea {!! $attributes->merge([
    'name' => $name,
    'id' => $id,
]) !!} {{ $required ? 'required' : '' }} {{ $autofocus ? 'autofocus' : '' }}
    @disabled($disabled) {!! $describedBy() ? 'aria-describedby="' . $describedBy() . '"' : '' !!} {!! $invalid ? 'aria-invalid="true"' : '' !!}>{{ $value ?? $slot }}</textarea>
