<x-app-layout>
    <x-slot name="title">{{ __('story.index_title') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('story.index_title') }}</h1>
    </x-slot>

   <div class="flow">
    @forelse($stories as $story)
    <article>
        <h2>
            <a href="{{ localized_route('stories.show', $story) }}">{{ $story->title }}</a>
        </h2>
    </article>
    @empty
    <p>{{ __('story.none_found') }}</p>
    @endforelse
    </div>
</x-app-layout>
