<form class="stack" method="POST" action="{{ localized_route('register-context') }}" novalidate>
    <fieldset class="field @error('context') field--error @enderror stack">
        <legend>{{ __('How will you be using this website?') }}</legend>
        <x-hearth-hint for="context">{{ __('If you fit more than one of the following groups, you’ll have to register separately.') }}</x-hearth-hint>
        <x-hearth-radio-buttons name="context" :options="[
            [
                'value' => 'individual',
                'label' => __('As a individual'),
                'hint' => __('I have lived experience of being disabled or Deaf, and I want to work on accessibility projects'),
            ],
            [
                'value' => 'organization',
                'label' => __('On behalf of a community organization'),
                'hint' => __('I am a part of an organization that serves the disability and Deaf community'),
            ],
            [
                'value' => 'regulated-organization',
                'label' => __('On behalf of a federally regulated organization'),
                'hint' => __('I am a part of an organization or business who wants to work on accessibility projects'),
            ],
            [
                'value' => 'regulated-organization-employee',
                'label' => __('As an employee of a federally regulated organization, looking for training'),
                'hint' => __('Completing trainings assigned by my organization or business'),
            ]
        ]" :checked="old('context', session('context')) ?? false" hinted />
        <x-hearth-error for="context" />
    </fieldset>

    <p class="repel">
        <a class="cta secondary" href="{{ localized_route('register', ['step' => 1]) }}">{{ __('Back') }}</a>

        <button>
            {{ __('Next') }}
        </button>
    </p>
    @csrf
</form>
