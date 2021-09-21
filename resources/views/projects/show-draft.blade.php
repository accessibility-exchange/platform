<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }} ({{ __('draft') }})</x-slot>
    <x-slot name="header">
        <h1>
            {{ $project->name }} ({{ __('draft') }})
        </h1>
    </x-slot>

    <h2>{{ __('Preview') }}</h2>

    <div class="preview flow">
        <div class="meta">
            <h3>{{ $project->name }}</h3>
            <p>{!! __('project.project_by', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</p>
            <p><strong>{{ __('Status:') }}</strong> {{ $project->state ? $project->state->label() : __('Drafting project page') }}</p>
            @if($project->started())
            <p><strong>{{ __('project.started_label') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
            @else
            <p><strong>{{ __('project.starting_label') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
            @endif
            @if($project->completed())
            <p><strong>{{ __('project.completed_label') }}:</strong> {{ $project->end_date->format('F Y') }}</p>
            @endif
        </div>

        <div class="flow" id="project-overview">
            <h3>{{ __('Project overview') }}</h3>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('project overview') . '</span>']) !!}</a></p>
            @endcan

            @include('projects.boilerplate.project-overview', ['level' => 4])
        </div>

        <div class="flow" id="who-were-looking-for">
            <h3>{{ __('Who we’re looking for') }}</h3>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('who we’re looking for') . '</span>']) !!}</a></p>
            @endcan

            @include('projects.boilerplate.who-were-looking-for', ['level' => 4])
        </div>

        <div class="flow" id="accessibility-and-accomodations">
            <h3>{{ __('Accessibility and accomodations') }}</h3>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('accessibility and accomodations') . '</span>']) !!}</a></p>
            @endcan

            @include('projects.boilerplate.accessibility-and-accomodations', ['level' => 4])
        </div>
    </div>

    <div class="steps flow">
        <h2>{{ __('Steps to publish') }}</h2>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('projects.edit', $project) }}">{{ __('Tell us who you’re looking for') }}</a><br />
            <small>{{ __('Completed') }}</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('projects.edit', $project) }}">{{ __('Access and accomodations') }}</a><br />
            <small>{{ __('Completed') }}</small>
        </p>

        @can('update', $project)
        @if($project->checkStatus('draft'))
        <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="publish" :value="__('Publish my project')" />
        </form>
        <p>{{ __('Once you publish your project, consultants can find and apply for your project.') }}</p>
        @endif
        @endcan
    </div>
</x-app-wide-layout>
