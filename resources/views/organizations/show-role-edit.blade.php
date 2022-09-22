<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit your role') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit your role') }}
        </h1>

        <h2>{{ __('Please tell us what you would like to do on this website.') }}</h2>
    </x-slot>

    <p>{{ __('You can always change this later.') }} <a
            href="{{ localized_route('about.for-community-organizations') }}">{{ __('Learn more about these roles') }}</a>
    </p>

    <form class="stack" action="{{ localized_route('organizations.save-roles', $organization) }}" method="post"
        novalidate>
        <fieldset class="field @error('roles') field--error @enderror">
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', $organization->roles ?? [])" />
            <x-hearth-error for="roles" />
        </fieldset>

        <p class="repel">
            <button class="secondary" type="button" x-on:click="history.back()">{{ __('Cancel') }}</button>
            <button>{{ __('Update') }}</button>
        </p>

        @method('put')
        @csrf
    </form>
</x-app-wide-layout>
