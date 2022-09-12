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
    </x-slot>

    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-language-preferences') }}" method="post">
        @csrf
        @method('put')

        <h2>{{ __('Website language') }}</h2>

        <p>{{ __('The languages you want to use for navigating this website.') }}</p>

        <div class="field @error('locale') field--error @enderror stack">
            <x-hearth-label for="locale" :value="__('Spoken or written language (required)')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', $user->locale)" />
            <x-hearth-error for="locale" />
        </div>

        <div class="field @error('signed_language') field--error @enderror stack">
            <x-hearth-label for="signed_language" :value="__('Signed language')" />
            <x-hearth-hint for="signed_language">
                {{ __('When content is available in the sign language you select, it will appear as a video.') }}
            </x-hearth-hint>
            <x-hearth-select name="signed_language" :options="$signedLanguages" :selected="old('signed_language', $user->signed_language)" hinted />
            <x-hearth-error for="signed_language" />
        </div>

        @if ($user->context === 'individual')
            <h2>{{ __('First language') }}</h2>

            <p>{{ __('Please indicate the language you are most comfortable using.') }}</p>

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

        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>
