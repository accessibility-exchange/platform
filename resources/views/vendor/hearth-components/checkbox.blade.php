<input type="checkbox" value="1" {!! $attributes->merge([
    'name' => $name,
    'id' => $id,
]) !!} {{ $required ? 'required' : '' }}
    {{ $autofocus ? 'autofocus' : '' }} @disabled($disabled) @checked($checked)
    {!! $describedBy() ? 'aria-describedby="' . $describedBy() . '"' : '' !!} {!! $invalid ? 'aria-invalid="true"' : '' !!}>
