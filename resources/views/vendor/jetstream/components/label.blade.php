@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-black']) }}>
    {{ $value ?? $slot }}
</label>
