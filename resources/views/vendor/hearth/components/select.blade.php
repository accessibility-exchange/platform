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
    @foreach($options as $option)
    <option value="{{ $option['value'] }}" @selected($selected == $option['value'])>{{ $option['label'] }}</option>
    @endforeach
</select>
