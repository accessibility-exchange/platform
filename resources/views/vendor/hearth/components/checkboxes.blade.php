@foreach($options as $option)
<div class="field">
    @php($hint = isset($option['hint']) ? $name . '-' . $option['value'] . '-hint' : '')
    <input {!! $attributes !!} type="checkbox" name="{{ $name }}[]" id="{{ $name }}-{{ $option['value'] }}" value="{{ $option['value'] }}" {!! $describedBy($hint) ? 'aria-describedby="' . $describedBy($hint) . '"' : '' !!} @checked(in_array($option['value'], $checked)) {!! $invalid ? 'aria-invalid="true"' : '' !!} />
    <x-hearth-label for="{{ $name }}-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
    @if(isset($option['hint']) && !empty($option['hint']))
    <x-hearth-hint for="{{ $name }}-{{ $option['value'] }}">{{ $option['hint'] }}</x-hearth-hint>
    @endif
</div>
@endforeach
