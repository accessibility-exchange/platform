@props(['status'])

@if ($status)
    <x-hearth-alert type="success" {{ $attributes->merge([]) }}>
        {{ $status }}
    </x-hearth-alert>
@endif
