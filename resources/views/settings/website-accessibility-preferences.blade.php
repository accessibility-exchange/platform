<x-app-layout>
    <x-slot name="title">{{ __('Website accessibility preferences') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Website accessibility preferences') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-website-accessibility-preferences') }}" method="POST"
        novalidate>
        @csrf
        @method('put')

        <fieldset class="field" x-data="previewHandler()">
            <legend>{{ __('Contrast adjustment') }}</legend>
            <x-hearth-hint for="theme">{{ __('Change the colour of the text and background.') }}</x-hearth-hint>
            @foreach ($themes as $theme)
                <div class="field h-10">
                    <x-hearth-radio-button name="theme" x-model.string="theme" :value="$theme['value']" :checked="old('theme', $user->theme)"
                        @change="preview()" hinted="theme-hint" />
                    <x-hearth-label :for="'theme-' . $theme['value']">
                        <x-theme-preview :for="$theme['value']" />
                        {{ $theme['label'] }}
                    </x-hearth-label>
                </div>
            @endforeach
            <script>
                function previewHandler() {
                    return {
                        theme: '{{ old('theme', $user->theme) }}',
                        preview() {
                            if (this.theme === 'system') {
                                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                    document.documentElement.dataset.theme = 'dark';
                                } else {
                                    document.documentElement.dataset.theme = 'light';
                                }
                            } else {
                                document.documentElement.dataset.theme = this.theme;
                            }
                        }
                    }
                }
            </script>
        </fieldset>

        <fieldset class="field @error('text_to_speech') field--error @enderror">
            <legend>{{ __('Text to speech') }}</legend>
            <x-hearth-hint for="text_to_speech">
                {{ __('You can play the page in spoken language. You can also highlight parts of this page, and they will be read out.') }}
            </x-hearth-hint>
            <x-hearth-radio-buttons name="text_to_speech" :options="\Spatie\LaravelOptions\Options::forArray([0 => __('Off'), 1 => __('On')])->toArray()" :checked="old('text_to_speech', $user->text_to_speech ?? false)"
                hinted="text_to_speech-hint" />
        </fieldset>

        <fieldset class="field @error('sign_language_translations') field--error @enderror">
            <legend>{{ __('Sign language translations') }}</legend>
            <x-hearth-hint for="sign_language_translations">
                {{ __('If a Sign Language video translation is available, you will see a button in line with the website content. Pressing that button will load the Sign Language video.') }}
            </x-hearth-hint>

            <x-hearth-radio-buttons name="sign_language_translations" :options="\Spatie\LaravelOptions\Options::forArray([0 => __('Off'), 1 => __('On')])->toArray()" :checked="old('sign_language_translations', $user->sign_language_translations ?? false)"
                hinted="sign_language_translations-hint" />
        </fieldset>

        <button>
            {{ __('Save changes') }}
        </button>
    </form>
</x-app-layout>
