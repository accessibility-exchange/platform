<x-app-layout page-width="wide" header-class="stack full header--resources">
    <x-slot name="title">{{ __('Resources') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <h1 itemprop="name">{{ __('Resources') }}</h1>
            <p class="subtitle">
                {{ __('Find learning materials, best practices, and variety of tools to help you throughout the consultation process.') }}
            </p>
            <x-interpretation name="{{ __('Resources', [], 'en') }}" />
        </div>
    </x-slot>

    <x-section class="px-0" aria-labelledby="search">
        <h2 class="h4" id="search">{{ __('Search for resources') }}</h2>
        <x-interpretation name="{{ __('Search for resources', [], 'en') }}" />
        <form class="search" action="{{ localized_route('resources.index') }}" method="get">
            <label class="visually-hidden" for="search">{{ __('Search') }}</label>
            <input id="search" name="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
    </x-section>
    <x-section class="full accent" aria-labelledby="resource-collections">
        <div class="center center:wide stack stack:xl">
            <h2 id="resource-collections">{{ __('Featured collections') }}</h2>
            <x-interpretation name="{{ __('Featured collections', [], 'en') }}" />
            @if ($resourceCollections->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($resourceCollections as $resourceCollection)
                        <x-card.resource-collection :model="$resourceCollection" />
                    @endforeach
                </div>
            @else
                <p>{{ __('resource-collection.none_found') }}</p>
            @endif
        </div>
    </x-section>
    <x-section class="px-0" aria-labelledby="trainings">
        <div class="center center:wide stack stack:xl px-0">
            <h2 id="trainings">{{ __('Featured trainings') }}</h2>
            <x-interpretation name="{{ __('Featured trainings', [], 'en') }}" />
            @if ($courses->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($courses as $course)
                        <x-card.course :model="$course" />
                    @endforeach
                </div>
            @else
                <p>{{ __('No trainings found.') }}</p>
            @endif
        </div>
    </x-section>
    <section class="darker full -mb-8 pb-16 pt-20" aria-labelledby="browse-all">
        <div class="center center:wide stack stack:xl text-center">
            <h2 class="md:mx-auto md:w-2/3" id="browse-all">
                {{ __('Is there something you are looking for that isn’t here?') }}</h2>
            <x-interpretation name="{{ __('Is there something you are looking for that isn’t here?', [], 'en') }}" />
            <p>
                <a class="cta" href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}</a>
            </p>
        </div>
    </section>
</x-app-layout>
