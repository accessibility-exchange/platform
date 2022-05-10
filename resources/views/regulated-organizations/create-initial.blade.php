<x-app-wide-layout>
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>
        @switch($type)
            @case('government')
                <h2>{{ __('Create new government organization') }}</h2>
                @break
            @case('business')
                <h2>{{ __('Create new business') }}</h2>
                @break
            @case('public-sector')
                <h2>{{ __('Create new public sector organization') }}</h2>
            @break
            @default
                <h2>{{ __('Create new regulated organization') }}</h2>
        @endswitch
    </x-slot>

    <form class="stack" action="{{ localized_route('regulated-organizations.store') }}" method="post" novalidate>
        <fieldset>
            <legend>{{ __('Your organization’s name') }}</legend>
            <div class="field @error('name.en') field--error @enderror">
                <x-hearth-label for="name-en">{{ __('Name of organization — English') }}</x-hearth-label>
                <x-hearth-input name="name[en]" id="name-en" :value="old('name.en', '')" />
                <x-hearth-error for="name.en" />
            </div>
            <div class="field @error('name.fr') field--error @enderror">
                <x-hearth-label for="name-fr">{{ __('Name of organization — French') }}</x-hearth-label>
                <x-hearth-input name="name[fr]" id="name-fr" :value="old('name.fr', '')" />
                <x-hearth-error for="name.fr" />
            </div>

            <x-hearth-input type="hidden" name="type" :value="$type" />
        </fieldset>

        <button>{{ __('Create') }}</button>

        @csrf
    </form>
</x-app-wide-layout>
