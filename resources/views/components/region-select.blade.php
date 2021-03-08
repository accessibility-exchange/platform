<select id="region" name="region">
    <option value=""></option>
    @foreach ($regions as $region)
    <option
        value="{{ $region }}"
        @if ($region === $selected)
        selected
        @endif
    >{{ __('regions.' . $region) }}</option>
    @endforeach
</select>
