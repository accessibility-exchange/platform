<x-app-wide-layout>
    <x-slot name="title">{{ __('Manage engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="{{ localized_route('projects.show', $engagement->project) }}">{{ $engagement->project->name }}</a>
            </li>
        </ol>
        <p class="mt-8"><strong>{{ __('Engagement') }}</strong></p>
        <h1 class="mt-0">
            {{ $engagement->name }}
        </h1>
    </x-slot>

    <p><a class="cta secondary"
            href="{{ localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]) }}">{{ __('Edit engagement details') }}</a>
    </p>
</x-app-wide-layout>
