<form class="stack" method="POST" action="{{ localized_route('register-context') }}" novalidate>

    <fieldset class="field @error('context') field--error @enderror stack">
        <legend>{{ __('How will you be using this website?') }}</legend>
        <x-hearth-hint for="context">{{ __('If you fit more than one of the following groups, you’ll have to register separately.') }}</x-hearth-hint>
        <x-hearth-radio-buttons name="context" :options="[
            'community-member' => [
                'label' => __('As a community member'),
                'hint' => __('I have lived experience of being disabled or Deaf, and I want to work on accessibility projects'),
            ],
            'organization' => [
                'label' => __('On behalf of a community organization'),
                'hint' => __('I am a part of an organization that serves the disability and Deaf community'),
            ],
            'entity' => [
                'label' => __('On behalf of a federally regulated organization'),
                'hint' => __('I am a part of an organization or business who wants to work on accessibility projects'),
            ]
        ]" :checked="old('context', session('context')) ?? false" hinted />
        <x-hearth-error for="context" />
    </fieldset>

    <x-hearth-button>
        {{ __('Next') }}
    </x-hearth-button>
    @csrf
</form>
