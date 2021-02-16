<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('user.edit_title', ['name' => $user->name]) }}
        </h1>
    </x-slot>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="name">{{ __('Full name') }}</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" />
        </div>
        <div class="field">
            <label for="locality">{{ __('City or town') }}</label>
            <input type="text" id="locality" name="locality" value="{{ $user->locality }}" />
        </div>
        <div class="field">
            <label for="region">{{ __('Province or territory') }}</label>
            <x-region-select :selected="$user->region" />
        </div>
        <div class="field">
            <label for="about">{{ __('About') }}</label>
            <textarea id="about" name="about">{{ $user->about }}</textarea>
        </div>
        <button type="submit">Save changes</button>
    </form>
</x-app-layout>
