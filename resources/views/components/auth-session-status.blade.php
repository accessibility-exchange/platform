@props(['status'])

@if ($status)
    <div {{ $attributes->merge([]) }}>
        {{ $status }}
    </div>
@endif
