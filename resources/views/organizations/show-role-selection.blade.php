<x-app-layout>
    <x-slot name="title">{{ __('Create new community organization') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create new community organization') }}
        </h1>
        <x-interpretation name="{{ __('Create new community organization', [], 'en') }}" />
    </x-slot>

    <form class="stack" action="{{ localized_route('organizations.save-roles', $organization) }}" method="post"
        novalidate>
        <fieldset class="field @error('type') field--error @enderror">
            <legend>{{ __('Your organization’s role') }}</legend>
            <x-interpretation name="{{ __('Your organization’s role', [], 'en') }}" />
            <x-hearth-hint for="roles">
                {{ __('Please tell us what your organization would like to do here. You must pick at least one of these roles. You can always change this later.') }}
                <a href="{{ localized_route('about.for-community-organizations') }}"
                    target="_blank">{{ __('Learn more about these roles') }}</a>
            </x-hearth-hint>
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', session()->get('roles') ?? [])" />
            <x-hearth-error for="roles" />
        </fieldset>

        <button>{{ __('Next') }}</button>

        @csrf
        @method('put')
    </form>
</x-app-layout>
