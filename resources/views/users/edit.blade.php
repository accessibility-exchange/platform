<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('user.edit_title', ['name' => $user->name]) }}
        </h1>
    </x-slot>

    <form action="{{ localized_route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="name">{{ __('user.label_name') }}</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" />
        </div>
        <div class="field">
            <label for="locality">{{ __('user.label_locality') }}</label>
            <input type="text" id="locality" name="locality" value="{{ $user->locality }}" />
        </div>
        <div class="field">
            <label for="region">{{ __('user.label_region') }}</label>
            <x-region-select :selected="$user->region" />
        </div>
        <div class="field">
            <label for="about">{{ __('user.label_about') }}</label>
            <textarea id="about" name="about">{{ $user->about }}</textarea>
        </div>

        <div class="field">
            <label for="locale">{{ __('user.label_locale') }}</label>
            <x-locale-select :selected="$user->locale" />
        </div>
        <button type="submit">{{ __('forms.save_changes') }}</button>
    </form>
</x-app-layout>
