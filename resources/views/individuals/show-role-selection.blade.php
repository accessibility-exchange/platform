<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>
        <x-interpretation name="{{ __('Welcome to') . ' ' . __('The Accessibility Exchange', [], 'en') }}" />

        <h2>{{ __('Please tell us what you would like to do on this website.') }}</h2>
        <x-interpretation name="{{ __('Please tell us what you would like to do on this website.', [], 'en') }}" />
    </x-slot>

    <p><strong>{{ __('You must pick at least one of these roles.') }}</strong>
        {{ __('You can always change this later.') }} <a
            href="{{ localized_route('about.for-individuals') }}">{{ __('Learn more about these roles') }}</a></p>

    <form class="stack" action="{{ localized_route('individuals.save-roles') }}" method="post" novalidate>
        <fieldset class="field @error('roles') field--error @enderror">
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', $defaultRoles)" />
            <x-hearth-error for="roles" />
        </fieldset>

        <button>{{ __('Continue') }}</button>

        @method('put')
        @csrf
    </form>
</x-app-layout>
