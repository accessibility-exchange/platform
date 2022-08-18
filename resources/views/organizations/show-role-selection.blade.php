<x-app-layout>
    <x-slot name="title">{{ __('Create new community organization') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create new community organization') }}
        </h1>

        <h2>{{ __('Create new community organization') }}</h2>
    </x-slot>

    <form class="stack" action="{{ localized_route('organizations.save-roles', $organization) }}" method="post" novalidate>
        <fieldset class="field @error('type') field--error @enderror">
            <legend>{{ __('What type of organization are you?') }}</legend>
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', session()->get('roles') ?? [])" />
            <x-hearth-error for="roles" />
        </fieldset>

        <button>{{ __('Next') }}</button>

        @csrf
        @method('put')
    </form>
</x-app-layout>
