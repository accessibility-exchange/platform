<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Collaboration Preferences') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('Dashboard') }}</a></li>
            @yield('breadcrumbs')
        </ol>
        <h1 class="mt-0" itemprop="name">
            {{ __('Enter your collaboration preferences') }}
        </h1>
        <p>{{ __('Please complete this section so that you can be set up to participate.') }}</p>
    </x-slot>

    @if ($individual->isParticipant())
        <h2>{{ __('Required') }}</h2>
        <ul class="link-list" role="list">
            <li>
                <a href="{{ localized_route('settings.edit-payment-information') }}">{{ __('Payment information') }}</a>
            </li>
        </ul>
    @endif

    <h2>{{ __('Recommended') }}</h2>
    <ul class="link-list" role="list">
        @if ($individual->isParticipant())
            <li>
                <a
                    href="{{ localized_route('settings.edit-access-needs') }}">{{ __('Access needs for consultations') }}</a>
            </li>
        @endif
        <li>
            <a
                href="{{ localized_route('settings.edit-communication-and-consultation-preferences') }}">{{ __('Communication and consultation preferences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('settings.edit-language-preferences') }}">{{ __('Language preferences') }}</a>
        </li>
        <li>
            <a
                href="{{ localized_route('settings.edit-areas-of-interest') }}">{{ __('Areas of accessibility you are interested in') }}</a>
        </li>
    </ul>

</x-app-layout>
