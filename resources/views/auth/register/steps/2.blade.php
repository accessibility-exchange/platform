<form class="stack" method="POST" action="{{ localized_route('register-context') }}" novalidate>
    <fieldset class="field @error('context') field--error @enderror stack">
        <legend>{{ __('Tell us about who you are.') }}</legend>
        <x-interpretation name="{{ __('Tell us about who you are.', [], 'en') }}" />
        <x-hearth-hint for="context">
            {{ __('If more than one of the following applies to you, you will need to register separate accounts.') }}
        </x-hearth-hint>
        <x-expander type="disclosure" :summary="__('Learn more')" level="3">
            <x-interpretation name="{{ __('Learn more', [], 'en') }}" />
            <p>
                {{ __('You can only choose one of these options for each account. So, if you are a Deaf person who would like to participate in consultations, and you are also the contact for an organization that serves Deaf people, you should create two separate accounts, one as an individual, and as a Community Organization. That way, we can be sure to show you the right information based on who you’ve joined as.') }}
            </p>
        </x-expander>
        <x-hearth-radio-buttons name="context" :options="$contexts" :checked="old('context', session('context')) ?? false" hinted />
        <x-hearth-error for="context" />
    </fieldset>

    <x-interpretation name="{{ __('Back', [], 'en') . '_' . __('Next', [], 'en') }}" namespace="back_next" />
    <p class="repel">
        <a class="cta secondary" href="{{ localized_route('register', ['step' => 1]) }}">{{ __('Back') }}</a>

        <button>
            {{ __('Next') }}
        </button>
    </p>
    @csrf
</form>
