<select id="region" name="region" data-filter-bind="region">
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
