<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('Users') }}</h1>
    </x-slot>

    <div class="flow">
        @foreach($users as $user)
        <p><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></p>
        @endforeach
    </div>
</x-app-layout>
