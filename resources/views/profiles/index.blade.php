<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
    @if($profiles)
        @foreach($profiles as $profile)
        <article>
            <h2>
                <a href="{{ localized_route('profiles.show', $profile) }}">{{ $profile->name }}</a>
            </h2>
            <p>{{ $profile->locality }}, {{ __('regions.' . $profile->region) }}</p>
        </article>
        @endforeach
    @else
        <p>{{ __('profile.none_found') }}</p>
    @endif
    </div>
</x-app-layout>
