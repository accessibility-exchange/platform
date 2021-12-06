<form method="POST" action="{{ localized_route('community-members.create-save-roles') }}" novalidate>

    <fieldset class="field @error('roles') field--error @enderror">
        <legend>{{ __('What do you want to do here?') }}</legend>
        <x-hearth-checkboxes name="roles" :options="[
            'participant' => __('Participant'),
            'consultant' => __('Consultant'),
            'connector' => __('Community connector')
        ]" :selected="old('roles', session('roles')) ?? []" />
        <x-hearth-error for="roles" />
    </fieldset>

    <a class="button" href="{{ localized_route('dashboard') }}">{{ __('Cancel') }}</a>

    <x-hearth-button>
        {{ __('Save and continue') }}
    </x-hearth-button>

    @csrf
</form>
