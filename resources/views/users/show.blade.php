<x-app-layout>
    <x-slot name="itemtype">Person</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ $user->name }}</h1>
        <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <span itemprop="addressLocality">{{ $user->locality }}</span>, <span itemprop="addressRegion">{{ $user->region }}</span>
        </p>
    </x-slot>

    <div class="flow">
        @if($user->bio)
        <div itemprop="description">
            {{ Illuminate\Mail\Markdown::parse($user->bio) }}
        </div>
        @endif
        @auth
        @if(Auth::user()->id === $user->id)
        <p><a href="{{ route('users.edit', $user->id) }}">{{ __('Edit Profile') }}</a></p>
        @endif
        @endauth
    </div>
</x-app-layout>
