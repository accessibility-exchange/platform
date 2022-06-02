<x-app-wide-layout>
    <x-slot name="title">{{ __('Roles and permissions') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Roles and permissions') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if($user->context == 'regulated-organization')
        @include('users.roles-and-permissions.manage', ['membershipable' => $regulatedOrganization])
    @elseif($user->context == 'organization')
        @include('users.roles-and-permissions.manage', ['membershipable' => $user->organization])
    @endif
</x-app-wide-layout>
