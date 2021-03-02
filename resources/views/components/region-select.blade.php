<select id="region" name="region">
    <option value=""></option>
    @foreach ($regions as $value => $region)
    <option
        value="{{ $value }}"
        @if ($value === $selected)
        selected
        @endif
    >{{ $region }}</option>
    @endforeach
</select>
