<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('resource.index_title') }}</h1>
    </x-slot>

   <div class="flow">
    @forelse($resources as $resource)
    <article>
        <h2>
            <a href="{{ localized_route('resources.show', $resource) }}">{{ $resource->title }}</a>
        </h2>
    </article>
    @empty
    <p>{{ __('resource.none_found') }}</p>
    @endforelse
    </div>
</x-app-layout>
