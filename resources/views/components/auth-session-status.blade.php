@props(['status'])

@if ($status)
    <x-live-region>
        <x-hearth-alert type="success" {{ $attributes->merge([]) }}>
            {{ $status }}
        </x-hearth-alert>
    </x-live-region>
@endif
