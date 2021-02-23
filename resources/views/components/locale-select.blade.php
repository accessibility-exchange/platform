<select id="locale" name="locale">
    @foreach($locales as $key => $locale)
    <option
        value="{{ $key }}"
        @if ($key === $selected)
        selected
        @endif
    >{{ $locale }}</option>
    @endforeach
</select>
