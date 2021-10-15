<x-app-layout>
    <x-slot name="title">{{ __('Browse all resources') }}</x-slot>
    <x-slot name="header">
        <p><a href="{{ localized_route('collections.index') }}">{{ __('Back to resource hub') }}</a></p>
        <h1 itemprop="name">{{ __('Browse all resources') }}</h1>
        <p class="subtitle">{{ __('Browse different resources to find what can help you throughout a consultation process based on your role and responsibilities.') }}</p>
    </x-slot>

    <div class="flow">
        <h2>{{ __('Search') }}</h2>
        <form class="search" action="" method="post">
            @csrf
            <label for="search" class="visually-hidden">{{ __('Search') }}</label>
            <input id="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
    </div>
    <div class="resources">
        <div class="filters flow">
            <h2 class="visually-hidden">{{ __('Filters') }}</h2>
            <div class="expander" x-data="{expanded: false}">
                <button @click="expanded = ! expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Popular topics') }} <x-heroicon-s-plus x-show="! expanded" class="icon" /><x-heroicon-s-minus x-show="expanded" class="icon" /></button>
                <div x-show="expanded">
                    <ul role="list">
                        @foreach($topics as $key => $label)
                        <li>
                            <x-hearth-input type="checkbox" id="topics-{{ $key }}" name="topics[]" value="{{ $key }}" />
                            <label for="topics-{{ $key }}">{{ $label }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="expander" x-data="{expanded: false}">
                <button @click="expanded = ! expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Content') }} <x-heroicon-s-plus x-show="! expanded" class="icon" /><x-heroicon-s-minus x-show="expanded" class="icon" /></button>
                <div x-show="expanded">
                    <ul role="list">
                        @foreach($types as $key => $label)
                        <li>
                            <x-hearth-input type="checkbox" id="type-{{ $key }}" name="type[]" value="{{ $key }}" />
                            <label for="type-{{ $key }}">{{ $label }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="expander" x-data="{expanded: false}">
                <button @click="expanded = ! expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Format') }} <x-heroicon-s-plus x-show="! expanded" class="icon" /><x-heroicon-s-minus x-show="expanded" class="icon" /></button>
                <div x-show="expanded">
                    <ul role="list">
                        @foreach($formats as $key => $label)
                        <li>
                            <x-hearth-input type="checkbox" id="format-{{ $key }}" name="format[]" value="{{ $key }}" />
                            <label for="format-{{ $key }}">{{ $label }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="expander" x-data="{expanded: false}">
                <button @click="expanded = ! expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Language') }} <x-heroicon-s-plus x-show="! expanded" class="icon" /><x-heroicon-s-minus x-show="expanded" class="icon" /></button>
                <div x-show="expanded">
                    <ul role="list">
                        @foreach($languages as $key => $label)
                        <li>
                            <x-hearth-input type="checkbox" id="language-{{ $key }}" name="language[]" value="{{ $key }}" />
                            <label for="language-{{ $key }}">{{ $label }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="expander" x-data="{expanded: false}">
                <button @click="expanded = ! expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Consultation process') }} <x-heroicon-s-plus x-show="! expanded" class="icon" /><x-heroicon-s-minus x-show="expanded" class="icon" /></button>
                <div x-show="expanded">
                    <ul role="list">
                        @foreach($process as $key => $label)
                        <li>
                            <x-hearth-input type="checkbox" id="process-{{ $key }}" name="process[]" value="{{ $key }}" />
                            <label for="process-{{ $key }}">{{ $label }}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="cards">
            @forelse($resources as $resource)
            <article class="card card--resource">
                <p><span class="visually-hidden">{{ __('Content type') }}:</span> {{ Arr::random($types) }}</p>
                <p><span class="visually-hidden">{{ __('Format') }}:</span> {{ Arr::random($formats) }} <span class="aria-hidden">&middot;</span> <span class="visually-hidden">{{ __('Language') }}:</span> {{ Arr::random($languages) }}</p>
                <h2>
                    <a href="{{ localized_route('resources.show', $resource) }}">{{ $resource->title }}</a>
                </h2>
                <p>{{ Str::limit($resource->summary, 140); }} <a href="{{ localized_route('resources.show', $resource) }}">{!! __('Read more <span class="visually-hidden">of :title</span>', ['title' => $resource->title]) !!}</a></p>
            </article>
            @empty
            <p>{{ __('resource.none_found') }}</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
