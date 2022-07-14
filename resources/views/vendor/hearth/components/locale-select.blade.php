<select {!! $attributes->merge([
        'name' => $name,
        'id' => $id,
    ]) !!}
    {{ $required ? 'required' : '' }}
    {{ $autofocus ? 'autofocus' : '' }}
    @disabled($disabled)
    {!! $describedBy() ? 'aria-describedby="' . $describedBy() . '"' : '' !!}
    {!! $invalid ? 'aria-invalid="true"' : '' !!}>
    @foreach($locales as $key => $locale)
    <option
        value="{{ $key }}"
        @if ($key === $selected)
        selected
        @endif
    >{{ $locale }}</option>
    @endforeach
</select>
