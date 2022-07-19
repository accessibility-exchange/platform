<x-app-layout>
    <x-slot name="title">{{ __('Settings') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Settings') }}
        </h1>
    </x-slot>

    <h2>{{ __('For consultations') }}</h2>
    @if($user->context === 'individual')
    <p>{{ __('Please provide personal information that will help us find consultations for you to participate in.') }}</p>
    @elseif($user->context === 'regulated-organization')
    <p>{{ __('Organization information that will set you up for running consultations.') }}</p>
    @endif
    <ul class="link-list" role="list">
        @if($user->context === 'individual')
        {{-- Matching --}}
        @endif
        @if($user->context === 'individual')
        <li><a href="{{ $user->individual->checkStatus('published') ? localized_route('individuals.show', $user->individual) : localized_route('individuals.edit', $user->individual) }}">{{ __('Public profile') }}</a></li>
        @elseif($user->context === 'organization')
        <li><a href="{{ $user->organization->checkStatus('published') ? localized_route('organizations.show', $user->organization) : localized_route('organizations.edit', $user->organization) }}">{{ __('Public profile') }}</a></li>
        @elseif($user->context === 'regulated-organization')
        <li><a href="{{ $user->regulatedOrganization->checkStatus('published') ? localized_route('regulated-organizations.show', $user->regulatedOrganization) : localized_route('regulated-organizations.edit', $user->regulatedOrganization) }}">{{ __('Public profile') }}</a></li>
        @endif
        @if($user->context === 'individual')
        <li><a href="{{ localized_route('settings.edit-access-needs') }}">{{ __('Access needs') }}</a></li>
        <li><a href="{{ localized_route('settings.edit-communication-and-consultation-preferences') }}">{{ __('Communication and consultation preferences') }}</a></li>
        @endif
        <li><a href="{{ localized_route('settings.edit-language-preferences') }}">{{ __('Language preferences') }}</a></li>
        @if($user->context === 'individual')
        <li><a href="{{ localized_route('settings.edit-payment-information') }}">{{ __('Payment information') }}</a></li>
        <li><a href="{{ localized_route('settings.edit-areas-of-interest') }}">{{ __('Areas of interest') }}</a></li>
        @endif
    </ul>
    <h2>{{ __('For this website') }}</h2>
    <p>{{ __('Please provide settings that will help you use this website.') }}</p>
    <ul class="link-list" role="list">
        <li><a href="{{ localized_route('users.edit_display_preferences') }}">{{ __('Website accessibility preferences') }}</a></li>
        @if($user->context === 'organization' || $user->context === 'regulated-organization')
        <li><a href="{{ localized_route('users.edit-roles-and-permissions') }}">{{ __('Roles and permissions') }}</a></li>
        @endif
        <li><a href="{{ localized_route('users.edit_notification_preferences') }}">{{ __('Notification preferences') }}</a></li>
        <li><a href="{{ localized_route('users.admin') }}">{{ __('Change password') }}</a></li>
        <li><a href="{{ localized_route('users.delete') }}">{{ __('Delete account') }}</a></li>
        @if($user->context === 'organization' || $user->context === 'regulated-organization')
        {{-- Delete your organization --}}
        @endif
    </ul>
</x-app-layout>
