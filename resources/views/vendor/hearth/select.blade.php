<select
    {!! $attributes->merge([
        'name' => $name,
        'id' => $id,
    ]) !!}
    {{ $required ? 'required' : '' }}
    {{ $autofocus ? 'autofocus' : '' }}
    @disabled($disabled)
    {!! $describedBy() ? 'aria-describedby="' . $describedBy() . '"' : '' !!}
    {!! $invalid ? 'aria-invalid="true"' : '' !!}
>
    @foreach($options as $option => $label)
    <option value="{{ $option }}" @selected($selected == $option)>{{ $label }}</option>
    @endforeach
</select>
