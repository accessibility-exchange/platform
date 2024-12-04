@foreach ($options as $option)
    <div class="field">
        @php($id = Str::slug("{$name}-{$option['value']}"))
        @php($hint = isset($option['hint']) ? $id . '-hint' : '')
        <input id="{{ $id }}" name="{{ $name }}" type="radio" value="{{ $option['value'] }}"
            {!! $attributes !!} {!! $describedBy($hint) ? 'aria-describedby="' . $describedBy($hint) . '"' : '' !!} @checked($checked == $option['value']) {!! $invalid ? 'aria-invalid="true"' : '' !!} />
        <x-hearth-label for="{{ $id }}">{{ $option['label'] }}</x-hearth-label>
        @if (isset($option['interpretation']['name']) && !empty($option['interpretation']['name']))
            @if (isset($option['interpretation']['namespace']) && !empty($option['interpretation']['namespace']))
                <x-interpretation name="{{ $option['interpretation']['name'] }}"
                    namespace="{{ $option['interpretation']['namespace'] }}" />
            @else
                <x-interpretation name="{{ $option['interpretation']['name'] }}" />
            @endif
        @endif
        @if (isset($option['hint']) && !empty($option['hint']))
            <x-hearth-hint for="{{ $id }}">{{ $option['hint'] }}</x-hearth-hint>
        @endif
    </div>
@endforeach
