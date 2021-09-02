<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $resource->title }}
        </h1>
        <p>{{ get_locale_name($resource->language, locale()) }}</p>
    </x-slot>

    {!! Illuminate\Mail\Markdown::parse($resource->summary) !!}

    @can('update', $resource)
    <p><a href="{{ localized_route('resources.edit', $resource) }}">{{ __('resource.edit_resource') }}</a></p>
    @endcan
</x-app-layout>
