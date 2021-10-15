<x-app-wide-layout>
    <x-slot name="title">{{ __('Resource hub') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('Resource hub') }}</h1>
        <p class="subtitle">{{ __('Find learning materials, best practices, and variety of tools to help you throughout the consultation process.') }}</p>
    </x-slot>

    <div class="flow">
        <h2>{{ __('Search') }}</h2>
        <form class="search" action="" method="post">
            @csrf
            <label for="search" class="visually-hidden">{{ __('Search') }}</label>
            <input id="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
        <h2>{{ __('Browse all resources') }}</h2>
        <p>{{ __('Explore our entire resource hub.') }}</p>
        <p><a href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}</a></p>
    </div>
    <div class="flow">
        <h2>{{ __('Resources based on your role') }}</h2>
        <div class="cards cards--collections">
            @foreach($roleCollections as $collection)
            <div class="card card--collection flow">
                <h3 id="{{ Str::slug($collection->title) }}">{{ $collection->title }}</h3>
                <p>{{ $collection->description }}</p>
                <p class="actions"><a class="button" href="{{ localized_route('collections.show', $collection)}}" aria-describedby="{{ Str::slug($collection->title) }}">{{ __('Visit resources') }}</a></p>
            </div>
            @endforeach
        </div>
        <h2>{{ __('Resources based on stages of consultation') }}</h2>
        <div class="cards cards--collections">
            @foreach($stageCollections as $collection)
            <div class="card card--collection flow">
                <h3 id="{{ Str::slug($collection->title) }}">{{ $collection->title }}</h3>
                <p>{{ $collection->description }}</p>
                <p class="actions"><a class="button" href="{{ localized_route('collections.show', $collection)}}" aria-describedby="{{ Str::slug($collection->title) }}">{{ __('Visit resources') }}</a></p>
            </div>
            @endforeach
        </div>
        <h2>{{ __('Resource based on topics') }}</h2>
        <div class="cards cards--collections">
            @foreach($otherCollections as $collection)
            <div class="card card--collection flow">
                <h3 id="{{ Str::slug($collection->title) }}">{{ $collection->title }}</h3>
                <p>{{ $collection->description }}</p>
                <p class="actions"><a class="button" href="{{ localized_route('collections.show', $collection)}}" aria-describedby="{{ Str::slug($collection->title) }}">{{ __('Visit resources') }}</a></p>
            </div>
            @endforeach
        </div>
    </div>
    <div class="flow">
        <h2>{{ __('Stories from Deaf and Disability communities') }}</h2>
        <p><a href="{{ localized_route('stories.index') }}">{{ __('Browse all stories') }}</a></p>
    </div>
</x-app-wide-layout>
