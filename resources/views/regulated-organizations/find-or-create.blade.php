<x-app-wide-layout>
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>

        <h2>{{ __('Find or create your regulated organization') }}</h2>
    </x-slot>

    <h3>{{ __('Search for your regulated organization') }}</h3>
    <p>{{ __('Check if your regulated organization is already on this website.') }}</p>

    <livewire:membershipable-search membershipable="App\Models\RegulatedOrganization" />

    <h3>{{ __('Create a new regulated organization') }}</h3>
    <p>{{ __('Create a new regulated organization if it’s not already on the website.') }}</p>

    <p>
        <a class="cta secondary" href="{{ localized_route('regulated-organizations.show-type-selection') }}">{{ __('Create new organization') }}</a>
    </p>
</x-app-wide-layout>
