<form class="stack" method="POST" action="{{ localized_route('register-languages') }}" novalidate>
    @csrf

    @if (request()->get('context'))
        <input name="context" type="hidden" value="{{ request()->get('context') }}" />
    @endif

    @if (request()->get('role'))
        <input name="role" type="hidden" value="{{ request()->get('role') }}" />
    @endif

    @if (request()->get('invitation'))
        <input name="invitation" type="hidden" value="{{ request()->get('invitation') }}" />
    @endif

    @if (request()->get('email'))
        <input name="email" type="hidden" value="{{ request()->get('email') }}" />
    @endif

    <fieldset class="stack">
        <legend>{{ __('Please tell us which language you would like to use on The Accessibility Exchange.') }}</legend>

        <p id="languages-hint">
            {{ __('Please choose the language or languages you would like to use on this website.') }}<br />
            {{ __('Later, you will have the chance to choose the language or languages for your consultations.') }}
        </p>

        <div class="field @error('locale') field--error @enderror stack">
            <x-hearth-label for="locale" :value="__('Spoken or written language (please choose one)')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', locale())" hinted="languages-hint" />
            <x-hearth-error for="locale" />
        </div>

        <div class="field @error('signed_language') field--error @enderror stack">
            <x-hearth-label for="signed_language" :value="__('Sign Language (optional)')" />
            <x-hearth-hint for="signed_language">
                {{ __('If you use Sign Language, you can select which Sign Language you use. When content is available in the Sign Language you select, the content will appear as a video.') }}
            </x-hearth-hint>
            <x-hearth-select name="signed_language" :options="Spatie\LaravelOptions\Options::forArray([
                'ase' => __('American Sign Language (ASL)'),
                'fcs' => 'Langue des signes québécoise (LSQ)',
            ])
                ->nullable(__('Choose a sign language…'))
                ->toArray()" :selected="old('signed_language', '')"
                hinted="languages-hint signed_language-hint" />
            <x-hearth-error for="signed_language" />
        </div>
    </fieldset>

    <p class="repel">
        <button>
            {{ __('Next') }}
        </button>
    </p>
</form>
