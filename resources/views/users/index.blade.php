<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('user.index_title') }}</h1>
    </x-slot>

    <div class="flow">
        @if($users)
            <filter-container class="filterable flow">
            <div class="field">
                <label for="region">{{ __('user.label_region') }}</label>
                <x-region-select />
            </div>
            <p aria-live="polite" data-filter-results="{{ __('user.singular') }}/{{ __('user.plural') }}"></p>
            @foreach($users as $user)
            <div data-filter-region="{{ $user->region }}">
                <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
            </div>
            @endforeach
            </filter-container>
        @else
            <p>{{ __('user.none_found') }}</p>
        @endif
    </div>
</x-app-layout>
