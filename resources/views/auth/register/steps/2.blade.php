<form class="stack" method="POST" action="{{ localized_route('register-context') }}" novalidate>
    <fieldset class="field @error('context') field--error @enderror stack">
        <legend>{{ __('Tell us about who you are joining the Accessibility Exchange as.') }}</legend>
        <x-hearth-hint for="context">{{ __('If more than one of these apply to you, you have the option to register additional accounts.') }}</x-hearth-hint>
        <x-hearth-radio-buttons name="context" :options="[
            [
                'value' => 'individual',
                'label' => __('As an individual'),
                'hint' => __('I have lived experience of being disabled or Deaf, and I want to work on accessibility projects.'),
            ],
            [
                'value' => 'organization',
                'label' => __('Community Organization'),
                'hint' => __('I am a part of a community organization that represents or serves the disability community, the Deaf community. Or, I am a part of a civil society.'),
            ],
            [
                'value' => 'regulated-organization',
                'label' => __('Regulated Organizations: Business, Federal Government and Public Sector Organizations'),
                'hint' => __('I work for a business, the federal government or a public sector organization regulated under the Accessible Canada Act.'),
            ],
            [
                'value' => 'regulated-organization-employee',
                'label' => __('Individual Seeking Training'),
                'hint' => __('I am an employee seeking training assigned by my organization or business.'),
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
