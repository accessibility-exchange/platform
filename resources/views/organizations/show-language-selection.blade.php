
<x-app-layout>
    <x-slot name="title">{{ __('Create organization profile') }}</x-slot>
    <x-slot name="header">
        <p class="h2">{{ __('Create organization profile') }}</p>
        <h1>
            {{ __('Page translations') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Please list any languages that you will be using to describe your organization.') }}</p>

    <form class="stack" action="{{ localized_route('organizations.store-languages', $organization) }}" method="post" novalidate>
        <x-translation-picker />

        <p class="repel">
            <a class="cta secondary" href="{{ localized_route('dashboard') }}">{{ __('Cancel') }}</a>
            <button>{{ __('Create organization') }}</button>
        </p>
        @csrf
    </form>
</x-app-layout>
