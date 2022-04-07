<form class="stack" method="POST" action="{{ localized_route('register-context') }}" novalidate>

    <fieldset class="field @error('context') field--error @enderror stack">
        <legend>{{ __('How will you be using this website?') }}</legend>
        <x-hearth-hint for="context">{{ __('If you fit more than one of the following groups, you’ll have to register separately.') }}</x-hearth-hint>
        <x-hearth-radio-buttons name="context" :options="[
            'community-member' => __('As a community member'),
            'organization' => __('On behalf of a community organization'),
            'regulated-organization' => __('On behalf of a federally regulated organization')
        ]" :checked="old('context', session('context')) ?? false" hinted />
        <x-hearth-error for="context" />
    </fieldset>

    <x-hearth-button>
        {{ __('Next') }}
    </x-hearth-button>
    @csrf
</form>
