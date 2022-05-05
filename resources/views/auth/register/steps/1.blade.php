

<form class="stack" method="POST" action="{{ localized_route('register-languages') }}" novalidate>
    @csrf
    <fieldset class="stack">
        <legend>{{ __('Pick your website language') }}</legend>

        <p>{{ __('Please select the language(s) in which you’ll be using the website. You will be able to choose a different language to work in for engagements if your first language isn’t shown here.') }}</p>

        <div class="field @error('locale') field--error @enderror stack">
            <x-hearth-label for="locale" :value="__('Spoken or written language (required)')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', locale())" />
            <x-hearth-error for="locale" />
        </div>

        <div class="field @error('signed_language') field--error @enderror stack">
            <x-hearth-label for="signed_language" :value="__('Signed language')" />
            <x-hearth-hint for="signed_language">{{ __('When content is available in the sign language you select, it will appear as a video.') }}</x-hearth-hint>
            <x-hearth-select name="signed_language" :options="['' => __('Choose a signed language…'), 'ase' => __('American Sign Language (ASL)'), 'fcs' => 'Langue des signes québécoise (LSQ)']" :selected="old('signed_language', '')" hinted />
            <x-hearth-error for="signed_language" />
        </div>
    </fieldset>

    <p class="repel">
        <button>
            {{ __('Next') }}
        </button>
    </p>
</form>
