@props(['status'])

@if ($status)
    @php
        $interpretationName = match ($status) {
            __('passwords.reset') => __('passwords.reset', [], 'en'),
            __('passwords.sent') => __('passwords.sent', [], 'en'),
            __('passwords.throttled') => __('passwords.throttled', [], 'en'),
            __('passwords.token') => __('passwords.token', [], 'en'),
            __('passwords.user') => __('passwords.user', [], 'en'),
            default => null,
        };
    @endphp
    <x-live-region>
        <x-hearth-alert type="success" {{ $attributes->merge([]) }}>
            @isset($interpretationName)
                <x-interpretation name="{{ $interpretationName }}" namespace="auth_session_status" />
            @endisset
            {{ $status }}
        </x-hearth-alert>
    </x-live-region>
@endif
