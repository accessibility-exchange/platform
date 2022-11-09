<x-app-wide-layout>
    <x-slot name="title">{{ __('Browse all resources') }}</x-slot>
    <x-slot name="header">
        <p><a href="{{ localized_route('resource-collections.index') }}">{{ __('Back to resource hub') }}</a></p>
        <h1 itemprop="name">{{ __('Browse all resources') }}</h1>
        <p class="subtitle">
            {{ __('Find learning materials, best practices, and tools to help you throughout your project.') }}
        </p>
    </x-slot>

    <div class="stack">
        <h2>{{ __('Search') }}</h2>
        <form class="search" action="" method="post">
            @csrf
            <label class="visually-hidden" for="search">{{ __('Search') }}</label>
            <input id="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
    </div>
    <div class="stack with-sidebar with-sidebar:2/3">
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
                            <x-hearth-input id="format-{{ $format['value'] }}" name="format[]" type="checkbox"
                                value="{{ $format['value'] }}" />
                            <label for="format-{{ $format['value'] }}">{{ $format['label'] }}</label>
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
                            <x-hearth-input id="phase-{{ $phase['value'] }}" name="phase[]" type="checkbox"
                                value="{{ $phase['value'] }}" />
                            <label for="phase-{{ $phase['value'] }}">{{ $phase['label'] }}</label>
                        </li>
                    @endforeach
                </ul>
            </x-expander>
        </div>
        <div class="md:pl-4">
            <div class="stack">
                @forelse($resources as $resource)
                    <x-card.resource :model="$resource" :level="2" />
                @empty
                    <p>{{ __('resource.none_found') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-wide-layout>
