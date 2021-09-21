<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $project->name }}
        </h1>
        <p>{!! __('project.project_by', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</p>
        <p><strong>{{ __('Status:') }}</strong> {{ $project->status }}</p>
        @if($project->started())
        <p><strong>{{ __('project.started_label') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
        @else
        <p><strong>{{ __('project.starting_label') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
        @endif
        @if($project->completed())
        <p><strong>{{ __('project.completed_label') }}:</strong> {{ $project->end_date->format('F Y') }}</p>
        @endif
        @if(!$project->completed() && Auth::user()->context === 'project')
        <x-hearth-button type="button">{{ __('I’m interested in consulting for this project') }}</x-hearth-button>
        @endif
        @can('update', $project)
        @if($project->checkStatus('published'))
            @if(!$project->hasBuiltTeam())
            <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish my project')" />
            </form>
            @endif
        @endif
        <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Manage my project') }}</a>
        @endcan
    </x-slot>

    <div class="tabs flow" x-data="tabs(window.location.hash ? window.location.hash.substring(1) : 'project-overview')" x-on:resize.window="enabled = window.innerWidth > 1023">
        <h2 x-show="!enabled">{{ __('Contents') }}</h2>
        <ul x-bind="tabList">
            <li x-bind="tabWrapper"><a href="#project-overview" x-bind="tab">{{ __('Project overview') }}</a></li>
            <li x-bind="tabWrapper"><a href="#who-were-looking-for" x-bind="tab">{{ __('Who we’re looking for') }}</a></li>
            <li x-bind="tabWrapper"><a href="#accessibility-and-accomodations" x-bind="tab">{{ __('Accessibility and accomodations') }}</a></li>
        </ul>

        <div class="flow" id="project-overview" x-bind="tabpanel">
            <h2>{{ __('Project overview') }}</h2>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('project overview') . '</span>']) !!}</a></p>
            @endcan

            @include('projects.boilerplate.project-overview', ['level' => 3])
        </div>

        <div class="flow" id="who-were-looking-for" x-bind="tabpanel">
            <h2>{{ __('Who we’re looking for') }}</h2>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('who we’re looking for') . '</span>']) !!}</a></p>
            @endcan

            @include('projects.boilerplate.who-were-looking-for', ['level' => 3])
        </div>

        <div class="flow" id="accessibility-and-accomodations" x-bind="tabpanel">
            <h2>{{ __('Accessibility and accomodations') }}</h2>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('accessibility and accomodations') . '</span>']) !!}</a></p>
            @endcan

            @include('projects.boilerplate.accessibility-and-accomodations', ['level' => 3])
        </div>
    </div>

</x-app-wide-layout>
