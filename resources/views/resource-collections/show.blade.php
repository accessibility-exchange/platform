<x-app-layout>
    <x-slot name="title">{{ $resourceCollection->title }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $resourceCollection->title }}
        </h1>
    </x-slot>

    {!! Illuminate\Mail\Markdown::parse($resourceCollection->description) !!}

    <div class="stack">
        <h2>{{ __('Search') }}</h2>
        <form class="search" action="" method="post">
            @csrf
            <label class="visually-hidden" for="search">{{ __('Search') }}</label>
            <input id="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
    </div>
    <div class="resources">
        <div class="filters stack">
            <h2 class="visually-hidden">{{ __('Filters') }}</h2>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Topic') }}</x-slot>
                <ul role="list">
                    @foreach ($topics as $topic)
                        <li>
                            <x-hearth-input id="topics-{{ $topic->id }}" name="topics[]" type="checkbox"
                                value="{{ $topic->id }}" />
                            <label for="topics-{{ $topic->id }}">{{ $topic->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Content type') }}</x-slot>
                <ul role="list">
                    @foreach ($types as $type)
                        <li>
                            <x-hearth-input id="type-{{ $type->id }}" name="type[]" type="checkbox"
                                value="{{ $type->id }}" />
                            <label for="type-{{ $type->id }}">{{ $type->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Format') }}</x-slot>
                <ul role="list">
                    @foreach ($formats as $format)
                        <li>
                            <x-hearth-input id="format-{{ $format->id }}" name="format[]" type="checkbox"
                                value="{{ $format->id }}" />
                            <label for="format-{{ $format->id }}">{{ $format->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Language') }}</x-slot>
                <ul role="list">
                    @foreach ($languages as $code)
                        <li>
                            <x-hearth-input id="language-{{ $code }}" name="language[]" type="checkbox"
                                value="{{ $code }}" />
                            <label for="language-{{ $code }}">{{ get_language_exonym($code) }}</label>
                        </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Phase of consultation') }}</x-slot>
                <ul role="list">
                    @foreach ($phases as $phase)
                        <li>
                            <x-hearth-input id="phase-{{ $phase->id }}" name="phase[]" type="checkbox"
                                value="{{ $phase->id }}" />
                            <label for="phase-{{ $phase->id }}">{{ $phase->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </x-expander>
        </div>
        <div class="stack cards">
            @forelse($resources as $resource)
                <article class="box card card--resource">
                    @if ($resource->contentType)
                        <p><span class="visually-hidden">{{ __('Content type') }}:</span>
                            {{ $resource->contentType->name }}
                        </p>
                    @endif
                    @if ($resource->formats->count())
                        <p><span class="visually-hidden">{{ __('Format') }}:</span>
                            {{ $resource->formats->first()->name }}
                            <span aria-hidden="true">&middot;</span>
                        </p>
                    @endif
                    <h2>
                        <a href="{{ localized_route('resources.show', $resource) }}">{{ $resource->title }}</a>
                    </h2>
                    <p>{{ Str::limit($resource->summary, 140) }} <a
                            href="{{ localized_route('resources.show', $resource) }}">{!! __('Read more <span class="visually-hidden">of :title</span>', ['title' => $resource->title]) !!}</a>
                    </p>
                </article>
            @empty
                <p>{{ __('resource.none_found') }}</p>
            @endforelse
        </div>
    </div>

    @can('update', $resourceCollection)
        <p><a
                href="{{ localized_route('resource-collections.edit', $resourceCollection) }}">{{ __('resource-collection.edit_resource_collection') }}</a>
        </p>
    @endcan
</x-app-layout>
