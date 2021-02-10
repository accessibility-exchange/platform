<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('People') }}</h1>
    </x-slot>

    <div class="flow">
        @if($users)
            @foreach($users as $user)
            <p><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></p>
            @endforeach
        @else
            <p>{{ __('No people found.') }}</p>
        @endif
    </div>
</x-app-layout>
