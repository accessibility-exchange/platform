<x-app-layout>
    <x-slot name="title">{{ __('Language preferences') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Language preferences') }}
        </h1>
        <x-interpretation name="{{ __('Language preferences', [], 'en') }}" />
    </x-slot>

    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-language-preferences') }}" method="post">
        @csrf
        @method('put')

        <h2>{{ __('Website language') }}</h2>
        <x-interpretation name="{{ __('Website language', [], 'en') }}" />

        <p>{{ __('The language you want to use for navigating this website.') }}</p>

        <div class="field @error('locale') field--error @enderror stack">
            <x-hearth-label for="locale" :value="__('Language') . ' ' . __('(required)')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', $user->locale)" />
            <x-hearth-error for="locale" />
        </div>

        @if ($user->context === 'individual')
            <h2>{{ __('First language') }}</h2>

            <p>{{ __('The language you are most comfortable using.') }}</p>

            <div class="field @error('first_language') field--error @enderror stack">
                <x-hearth-label for="first_language" :value="__('First language')" />
                <x-hearth-select name="first_language" :options="$languages" :selected="old('first_language', $individual->first_language)" hinted />
                <x-hearth-error for="first_language" />
            </div>

            <fieldset class="field @error('working_languages.*') field--error @enderror stack">
                <legend>
                    <h2>{{ __('Working languages') }}</h2>
                </legend>

                <p>{{ __('The languages you can work in.') }}</p>

                <livewire:language-picker name="working_languages" :languages="old(
                    'working_languages',
                    !empty($individual->working_languages) ? $individual->working_languages : $workingLanguages,
                )" :availableLanguages="$languages" />
                <x-hearth-error for="working_languages.*" />
            </fieldset>
        @endif

        <x-interpretation name="{{ __('Save', [], 'en') }}" namespace="save" />
        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>
