<x-app-layout>
    <x-slot name="title">{{ __('Settings') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Settings') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <ul class="link-list" role="list">
        <li><a href="{{ localized_route('users.edit') }}">{{ __('Basic information') }}</a></li>
        <li><a href="{{ localized_route('users.edit_roles_and_permissions') }}">{{ __('Roles and permissions') }}</a></li>
        <li><a href="{{ localized_route('users.edit_display_preferences') }}">{{ __('Display preferences') }}</a></li>
        <li><a href="{{ localized_route('users.edit_notification_preferences') }}">{{ __('Notification preferences') }}</a></li>
        <li><a href="{{ localized_route('users.admin') }}">{{ __('Password and security') }}</a></li>
        <li><a href="{{ localized_route('users.delete') }}">{{ __('Delete account') }}</a></li>
    </ul>

</x-app-layout>
