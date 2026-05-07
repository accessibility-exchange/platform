<x-notification :notification="$notification">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="body">{{ $body }}</x-slot>
    @if ($showManageAccountsAction)
        <x-slot name="actions">
            <a class="cta secondary" href="{{ localized_route('admin.manage-accounts') }}">{{ __('Manage accounts') }}</a>
        </x-slot>
    @endif
</x-notification>
