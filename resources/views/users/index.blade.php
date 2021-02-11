<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('People') }}</h1>
    </x-slot>

    <div class="flow">
        @if($users)
            <filter-container class="flow">
            <div class="field">
                <label for="region">{{ __('Province or territory') }}</label>
                <x-region-select />
            </div>
            <p aria-live="polite" data-filter-results="{{ __('person') }}/{{ __('people') }}"></p>
            @foreach($users as $user)
            <div data-filter-region="{{ $user->region }}">
                <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
            </div>
            @endforeach
            </filter-container>
        @else
            <p>{{ __('No people found.') }}</p>
        @endif
    </div>
</x-app-layout>
