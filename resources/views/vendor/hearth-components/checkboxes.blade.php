@foreach ($options as $option)
    <div class="field">
        @php($id = Str::slug("{$name}-{$option['value']}"))
        @php($hint = isset($option['hint']) ? $id . '-hint' : '')
        <input id="{{ $id }}" name="{{ $name }}[]" type="checkbox" value="{{ $option['value'] }}"
            {!! $attributes !!} {!! $describedBy($hint) ? 'aria-describedby="' . $describedBy($hint) . '"' : '' !!} @checked(in_array($option['value'], $checked)) {!! $invalid ? 'aria-invalid="true"' : '' !!} />
        <x-hearth-label for="{{ $id }}">{{ $option['label'] }}</x-hearth-label>
        @if (isset($option['hint']) && !empty($option['hint']))
            <x-hearth-hint for="{{ $id }}">{{ $option['hint'] }}</x-hearth-hint>
        @endif
    </div>
@endforeach
