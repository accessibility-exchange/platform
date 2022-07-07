<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit your role') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit your role') }}
        </h1>

        <h2>{{ __('What would you like to do on this website?') }}</h2>
    </x-slot>

    <p>{{ __('You can always change this later.') }} <a href="{{ localized_route('about.for-individuals') }}">{{ __('Learn more about these roles') }}</a></p>

    <form class="stack" action="{{ localized_route('individuals.save-roles') }}" method="post" novalidate x-data="{initialRoles: {{ json_encode($selectedRoles) }}, roles: {{ old('roles', json_encode($selectedRoles) ?? '[]') }}}">
        <fieldset class="field @error('roles') field--error @enderror">
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', $selectedRoles ?? [])" x-model.number="roles" />
            <x-hearth-error for="roles" />
        </fieldset>

        <div role="alert">
            <x-hearth-alert type="warning" x-cloak x-show="(initialRoles.includes(2) || initialRoles.includes(3)) && !roles.includes(2) && !roles.includes(3)">
                {{ __('Your role no longer includes the accessibility consultant or community connector roles. Your public profile will be unpublished. However, if you edit your role to add the accessibility consultant or community connector roles again, you will be able to publish your profile again.') }}
            </x-hearth-alert>
        </div>

        <p class="repel">
            <button type="button" class="secondary" x-on:click="history.back()">{{ __('Cancel') }}</button>
            <button>{{ __('Update') }}</button>
        </p>

        @method('put')
        @csrf
    </form>
</x-app-wide-layout>
