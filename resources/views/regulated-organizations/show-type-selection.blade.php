<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>
        <x-interpretation name="{{ __('Welcome to The Accessibility Exchange', [], 'en') }}" />
    </x-slot>

    <form class="stack" action="{{ localized_route('regulated-organizations.store-type') }}" method="post" novalidate>
        <fieldset class="field @error('type') field--error @enderror">
            <legend>{{ __('Please identify the type of Regulated Organization yours is:') }}</legend>
            <x-interpretation
                name="{{ __('Please identify the type of Regulated Organization yours is:', [], 'en') }}" />
            <x-hearth-radio-buttons name="type" :options="$types" :checked="old('type', '')" />
            <x-hearth-error for="type" />
        </fieldset>

        <button>{{ __('Next') }}</button>

        @csrf
    </form>
</x-app-layout>
