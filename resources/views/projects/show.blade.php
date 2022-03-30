<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1 id="project">
            {{ $project->name }}
        </h1>
        <p><strong>{!! __('Accessibility project by :entity', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</strong></p>
        <p><strong>{{ __('Project status') }}:</strong> @if($project->started()){{ __('In progress') }}@else{{ __('Not started') }}@endif</p>
        @if($project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @endif
        @if(Auth::user()->communityMember)
        @if(!Auth::user()->communityMember->projectsOfInterest->contains($project->id))
        <form action="{{ localized_route('community-members.express-interest', Auth::user()->communityMember) }}" method="post">
            @csrf
            <x-hearth-input type="hidden" name="project_id" :value="$project->id" />
            <x-hearth-button type="submit">{{ __('I’m interested in this project') }}</x-hearth-button>
        </form>
        @else
        <form action="{{ localized_route('community-members.remove-interest', Auth::user()->communityMember) }}" method="post">
            @csrf
            <x-hearth-input type="hidden" name="project_id" :value="$project->id" />
            <x-hearth-button type="submit">{{ __('I’m not interested in this project') }}</x-hearth-button>
        </form>
        @endif
        @endif
        @can('update', $project)
        @if($project->checkStatus('published'))
            <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish')" />
            </form>
        @endif
        <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Project dashboard') }}</a>
        @endcan
    </x-slot>

    <div class="with-sidebar">
        <div class="stack">
            {{-- TODO: Sidebar. --}}
        </div>
        <div class="stack">
            <h2>{{ __('Project overview') }}</h2>

            @if($project->goals)
            <h3>{{ __('Project goals') }}</h3>

            <x-markdown class="stack">{{ $project->goals }}</x-markdown>
            @endif

            @if($project->scope || $project->impacts || $project->out_of_scope)
                <h3>{{ __('Project impact') }}</h3>

                @if($project->scope)
                <h4>{{ __('Who will this project impact?') }}</h4>

                <x-markdown class="stack">{{ $project->scope }}</x-markdown>
                @endif

                @if($project->impacts)
                <h4>{{ __('What areas of your organization will this project impact?') }}</h4>

                <ul role="list" class="tags">
                    @foreach($project->impacts as $impact)
                    <li class="tag">{{ $impact->name }}</li>
                    @endforeach
                </ul>
                @endif

                @if($project->out_of_scope)
                <h4>{{ __('What is out of scope?') }}</h4>

                <x-markdown class="stack">{{ $project->out_of_scope }}</x-markdown>
                @endif
            @endif

            @if($project->start_date || $project->end_date)
            <h3>{{ __('Project timeframe') }}</h3>

            <p>{!! $project->timespan() !!}</p>
            @endif

            @if($project->outcomes)
            <h3>{{ __('Project outcomes') }}</h3>

            <h4>{{ __('What are the tangible outcomes of this project?') }}</h4>

            <x-markdown class="stack">{{ $project->outcomes }}</x-markdown>
            @endif

            {{-- TODO: Engagements. --}}
        </div>
    </div>

</x-app-wide-layout>
