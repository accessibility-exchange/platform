<x-app-layout>
    <x-slot name="header">
        <h1>
            Edit {{ $user->name }}
        </h1>
    </x-slot>

    <div>
        @auth
        @if(Auth::user()->id === $user->id)
        You're allowed.
        @endif
        @endauth
    </div>
</x-app-layout>
