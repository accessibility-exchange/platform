<x-notification :notification="$notification">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="body">{{ $body }}</x-slot>
    <x-slot name="actions">
        <a class="cta secondary"
            href="{{ localized_route('organizations.edit', $organization) }}">{{ __('Edit my organization’s page') }}</a>
    </x-slot>
</x-notification>
