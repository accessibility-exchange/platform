<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $user->name }}
        </h1>
    </x-slot>

    <div>
        @auth
        @if(Auth::user()->id === $user->id)
        {{-- <a href="{{ route('users.edit', $user->id) }}">Edit Profile</a> --}}
        <a href="#">Edit Profile</a>
        @endif
        @endauth
    </div>
</x-app-layout>
