<x-app-layout>
    <x-slot name="title">{{ __('Browse all resources') }}</x-slot>
    <x-slot name="header">
        <p><a href="{{ localized_route('resources.hub') }}">{{ __('Back to resource hub') }}</a></p>
        <h1 itemprop="name">{{ __('Browse all resources') }}</h1>
        <p class="subtitle">{{ __('Browse different resources to find what can help you throughout a consultation process based on your role and responsibilities.') }}</p>
    </x-slot>

   <div class="resources">
    @forelse($resources as $resource)
    <article class="card resource--card">
        <h2>
            <a href="{{ localized_route('resources.show', $resource) }}">{{ $resource->title }}</a>
        </h2>
    </article>
    @empty
    <p>{{ __('resource.none_found') }}</p>
    @endforelse
    </div>
</x-app-layout>
