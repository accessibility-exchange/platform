<select {{ $attributes->merge([]) }}>
    @foreach($options as $option => $label)
    <option
        value="{{ $option }}"
        @if ($option === $selected)
        selected
        @endif
    >{{ $label }}</option>
    @endforeach
</select>
