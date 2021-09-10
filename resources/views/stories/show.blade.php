<x-app-layout>
    <x-slot name="title">{{ $story->title }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $story->title }}
        </h1>
        <p>{{ get_locale_name($story->language, locale()) }}</p>
    </x-slot>

    {!! Illuminate\Mail\Markdown::parse($story->summary) !!}

    @can('update', $story)
    <p><a href="{{ localized_route('stories.edit', $story) }}">{{ __('story.edit_story') }}</a></p>
    @endcan
</x-app-layout>
