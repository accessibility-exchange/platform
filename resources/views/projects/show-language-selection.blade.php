
<x-app-layout>
    <x-slot name="title">{{ __('Create new project') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        </ol>
        <p class="h3">{{ __('Create new project') }}</p>
        <h1>
            {{ __('Project translations') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('You can add different language translations to your project. If you pick a language here, you will have to add the translation yourself in the following pages.') }}</p>

    <x-hearth-alert :title="__('Translations')">
       <p>{{ __('It is highly recommended that you add French, ASL, and LSQ translations to your content.') }}</p>
    </x-hearth-alert>

    <form class="stack" action="{{ localized_route('projects.store-languages') }}" method="post" novalidate>
        <x-translation-picker />

        <p class="repel" x-data>
            <a class="cta secondary" href="{{ session()->has('ancestor') ? localized_route('projects.show-context-selection') : localized_route('dashboard') }}">{{ session()->has('ancestor') ? __('Back') : __('Cancel') }}</a>
            <button>{{ __('Next') }}</button>
        </p>
        @csrf
    </form>
</x-app-layout>
