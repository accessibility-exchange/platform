<x-app-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            <small>{{ __('Project dashboard') }}</small><br />
            {{ $project->name }}
        </h1>
        @if($project->started())
        <p><strong>{{ __('project.started_label') }}:</strong> {{ $project->start_date->format('F Y') }} &middot; <a href="{{ localized_route('projects.show', $project) }}">See published project</a></p>
        @endif
    </x-slot>

    <section>
        @include("projects.steps.$step")
    </section>
</x-app-layout>
