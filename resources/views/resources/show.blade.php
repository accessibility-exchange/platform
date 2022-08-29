<x-app-layout>
    <x-slot name="title">{{ $resource->title }}</x-slot>
    <x-slot name="header">
        <p><span class="visually-hidden">{{ __('Content type:') }}</span>
            {{ $resource->contentType->name ?? __('General resources') }}</p>
        <h1>
            {{ $resource->title }}
        </h1>
        <p>{{ __('Created by :creator', ['creator' => $resource->creator ?? __('The Accessibility Exchange')]) }} <span
                class="separator" aria-hidden="true">&middot;</span><span
                class="visually-hidden">{{ __('Published:') }}</span> {{ $resource->published() }}</p>
    </x-slot>

    {!! Illuminate\Mail\Markdown::parse($resource->summary) !!}

    @can('update', $resource)
        <p><a href="{{ localized_route('resources.edit', $resource) }}">{{ __('resource.edit_resource') }}</a></p>
    @endcan
</x-app-layout>
