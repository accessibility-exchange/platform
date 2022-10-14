<x-notification :notification="$notification">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="body">{{ $body }}</x-slot>
    <x-slot name="actions">
        <a class="cta secondary"
            href="{{ localized_route('engagements.manage-participants', $engagement) }}">{{ __('Manage participants') }}</a>
    </x-slot>
</x-notification>
