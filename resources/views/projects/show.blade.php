<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1 id="project">
            {{ $project->name }}
        </h1>
        <p>{!! __('Accessibility project by :entity', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</p>
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

                <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish my project')" />
            </form>
        @endif
        <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Project dashboard') }}</a>
        @endcan
    </x-slot>

    @php($level = 2)

    <x-heading :level="$level">{{ __('Goals for consultation') }}</x-heading>

    <x-markdown class="stack">{{ $project->goals }}</x-markdown>

    <x-heading :level="$level">{{ __('Project impact') }}</x-heading>

    <x-heading :level="$level + 1">{{ __('Who will this project impact?') }}</x-heading>

    <x-markdown class="stack">{{ $project->scope }}</x-markdown>

    <x-heading :level="$level + 1">{{ __('What areas of your organization will this project impact?') }}</x-heading>

    <ul role="list" class="tags">
        @foreach($project->impacts as $impact)
        <li class="tag">{{ $impact->name }}</li>
        @endforeach
    </ul>

    <x-heading :level="$level + 1">{{ __('What is this project not going to do?') }}</x-heading>

    <x-markdown class="stack">{{ $project->out_of_scope }}</x-markdown>


</x-app-wide-layout>
