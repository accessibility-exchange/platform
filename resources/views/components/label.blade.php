@props(['value'])

<label {{ $attributes->merge([]) }}>
    {{ $value ?? $slot }}
</label>
