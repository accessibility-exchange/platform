<x-app-layout>
    <x-slot name="title">{{ __('Create new project') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        </ol>
        <p class="h3">{{ __('Create a new project') }}</p>
        <h1>
            {{ __('Project Translations') }}
        </h1>
        <x-interpretation name="{{ __('Project Translations', [], 'en') }}" />
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Please select the languages that your project information can be translated into by your organization.') }}
    </p>

    @include('partials.translations-recommendation')

    <form class="stack" action="{{ localized_route('projects.store-languages') }}" method="post" novalidate>
        <h2>
            {{ __('Selected languages') }}
        </h2>
        <x-interpretation name="{{ __('Selected languages', [], 'en') }}" />
        <x-translation-picker />

        @if (session()->has('ancestor'))
            <x-interpretation name="{{ __('Back', [], 'en') . _ . __('Next', [], 'en') }}" namespace="back_next" />
        @else
            <x-interpretation name="{{ __('Cancel', [], 'en') . _ . __('Next', [], 'en') }}" namespace="cancel_next" />
        @endif
        <p class="repel">
            <a class="cta secondary"
                href="{{ session()->has('ancestor') ? localized_route('projects.show-context-selection') : localized_route('dashboard') }}">{{ session()->has('ancestor') ? __('Back') : __('Cancel') }}</a>
            <button>{{ __('Next') }}</button>
        </p>
        @csrf
    </form>
</x-app-layout>
