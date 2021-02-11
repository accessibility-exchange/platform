<x-app-layout>
    <x-slot name="itemtype">Person</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ $user->name }}</h1>
        @if($user->locality && $user->region)
        <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <span itemprop="addressLocality">{{ $user->locality }}</span>, <span itemprop="addressRegion">{{ __("geography.$user->region") }}</span>
        </p>
        @endif
    </x-slot>

    <div class="flow">
        @if($user->about)
        <div itemprop="description">
            {{ $user->about }}
        </div>
        @endif
        @auth
        @if(Auth::user()->id === $user->id)
        <p><a href="{{ route('users.edit', $user) }}">{{ __('Edit Profile') }}</a></p>
        @endif
        @endauth
    </div>
</x-app-layout>
