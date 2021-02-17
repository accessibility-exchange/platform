<select id="locale" name="locale" >
    @foreach($locales as $value => $label)
    <option
        value="{{ $value }}"
        @if ($value === $selected)
        selected
        @endif
    >{{ $label['native'] }}</option>
    @endforeach
</select>
