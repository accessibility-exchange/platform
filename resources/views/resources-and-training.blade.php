<x-app-layout page-width="wide" header-class="stack full header--libraries">
    <x-slot name="title">{{ __('Resources and training') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <h1 itemprop="name">{{ __('Resources and training') }}</h1>
            <p class="subtitle">
                {{ __('Find guidelines, learning materials, best practices, and featured trainings to help you throughout your consultation process.') }}
            </p>
            <a class="cta" href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}</a>
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
    <x-section class="full dark" aria-labelledby="overview">
        <div class="center center:wide stack stack:xl">
            <h2 id="overview">{{ __('How this is organized') }}</h2>
            <x-interpretation name="{{ __('How this is organized', [], 'en') }}" />
            <div class="grid">
                <div class="flex h-full flex-col space-y-3">
                    <div class="resources-and-training-symbol">
                        @svg('tae-libraries', ['class' => 'tae-libraries'])
                    </div>
                    <h3>{{ __('Libraries') }}</h3>
                    <p>{{ __('A set of collections grouped together to form a library about a larger topic.') }}</p>
                    <p><a class="flex items-center" href="#libraries">{{ __('Go to featured libraries') }}
                            @svg('heroicon-o-arrow-down', 'ml-1 icon--sm')</a></p>
                </div>
                <div class="flex h-full flex-col space-y-3">
                    <div class="resources-and-training-symbol">
                        @svg('tae-resource-collections', ['class' => 'tae-resource-collections'])
                    </div>
                    <h3>{{ __('Collections') }}</h3>
                    <p>{{ __('A set of resources collected together about a specific topic.') }}</p>
                    <p><a class="flex items-center" href="#resource-collections">{{ __('Go to featured collections') }}
                            @svg('heroicon-o-arrow-down', 'ml-1 icon--sm')</a></p>
                </div>
                <div class="flex h-full flex-col space-y-3">
                    <div class="resources-and-training-symbol">
                        @svg('tae-resources', ['class' => 'tae-resources'])
                    </div>
                    <h3>{{ __('Resources') }}</h3>
                    <p>{{ __('Specific documents, articles, or videos that help you learn about a specific topic.') }}
                    </p>
                    <p><a class="flex items-center"
                            href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}
                            @svg('heroicon-o-chevron-right', 'ml-1 icon--sm')</a></p>
                </div>
            </div>
        </div>
    </x-section>
    <x-section class="px-0" aria-labelledby="libraries">
        <div class="center center:wide stack stack:xl px-0">
            <h2 id="libraries">{{ __('Featured libraries') }}</h2>
            <x-interpretation name="{{ __('Featured libraries', [], 'en') }}" />
            @if ($featuredLibraries->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($featuredLibraries->take(4) as $library)
                        <x-card.library :model="$library" />
                    @endforeach
                </div>
                @if ($totalLibraries > $featuredLibraries->count())
                    <p class="text-right"><a class="inline-flex items-center"
                            href="{{ localized_route('libraries.index') }}">{{ __('Browse all libraries') }}
                            @svg('heroicon-s-chevron-right', 'ml-1 icon--sm')</a></p>
                @endif
            @else
                <p>{{ __('No libraries found.') }}</p>
            @endif
        </div>
    </x-section>
    <x-section class="full accent" aria-labelledby="resource-collections">
        <div class="center center:wide stack stack:xl">
            <h2 id="resource-collections">{{ __('Featured collections') }}</h2>
            <x-interpretation name="{{ __('Featured collections', [], 'en') }}" />
            @if ($featuredResourceCollections->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($featuredResourceCollections->take(4) as $resourceCollection)
                        <x-card.resource-collection :model="$resourceCollection" />
                    @endforeach
                </div>
                @if ($totalResourceCollections > $featuredResourceCollections->count())
                    <p class="text-right"><a class="inline-flex items-center"
                            href="{{ localized_route('resource-collections.index') }}">{{ __('Browse all collections') }}
                            @svg('heroicon-s-chevron-right', 'ml-1 icon--sm')</a></p>
                @endif
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
    <section class="full dark -mb-8 pb-16 pt-20" aria-labelledby="browse-all">
        <div class="center center:wide stack stack:xl text-center">
            <h2 class="md:mx-auto md:w-2/3" id="browse-all">
                {{ __('Is there something you are looking for that isn’t here?') }}
            </h2>
            <x-interpretation class="interpretation--center"
                name="{{ __('Is there something you are looking for that isn’t here?', [], 'en') }}" />
            <p>
                <a class="cta" href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}</a>
            </p>
        </div>
    </section>
</x-app-layout>
