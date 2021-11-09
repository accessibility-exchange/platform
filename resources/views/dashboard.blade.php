<x-app-layout>
    <x-slot name="title">{{ __('dashboard.title') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('dashboard.your_title', ['name' => Auth::user()->name]) }}</h1>
    </x-slot>

    @if(Auth::user()->context === 'consultant')
        @if(!Auth::user()->consultant)

        <p>{{ __('dashboard.consultant_things_you_can_do') }}</p>

        <h2>{{ __('dashboard.consultant_create_page_title') }}</h2>

        <p>{{ __('dashboard.consultant_create_page_info') }}</p>

        <p><a href="{{ localized_route('consultants.create') }}">{!! __('dashboard.create_page_prompt', ['item' => __('consultant.singular_title_lower')]) !!}</a></p>

        @else
        <p>
            <a href="{{ localized_route('consultants.show', ['consultant' => Auth::user()->consultant]) }}"><strong>{{ Auth::user()->consultant->name }}</strong>@if(Auth::user()->consultant->checkStatus('draft')) ({{ __('consultant.status_draft') }})@endif</a><br />
            <a href="{{ localized_route('consultants.edit', ['consultant' => Auth::user()->consultant]) }}">{{ __('consultant.edit_consultant_page') }}</a>
        </p>
        @endif

        <h2>{{ __('dashboard.consultant_learn_title') }}</h2>

        <p>{{ __('dashboard.consultant_learn_info') }}</p>

        <p><a href="{{ localized_route('collections.index') }}">{{ __('dashboard.learn_prompt') }}</a></p>

    @elseif (Auth::user()->context === 'entity')

        <p>{{ __('dashboard.entity_things_you_can_do') }}</p>

        @if(count(Auth::user()->entities) > 0)
        <h2>{{ __('dashboard.entity_manage_page_title') }}</h2>

        @foreach(Auth::user()->entities as $entity)
        <p><a href="{{ localized_route('entities.show', $entity) }}"><strong>{{ $entity->name }}</strong></a></p>
        @endforeach

        @else
        <h2>{{ __('dashboard.entity_create_page_title') }}</h2>

        <p>{{ __('dashboard.entity_create_page_info') }}</p>

        <p><a href="{{ localized_route('entities.create') }}">{!! __('dashboard.create_page_prompt', ['item' => __('regulated entity')]) !!}</a></p>
        @endif

        <h2>{{ __('dashboard.entity_learn_title') }}</h2>

        <p>{{ __('dashboard.entity_learn_info') }}</p>

        <p><a href="{{ localized_route('collections.index') }}">{{ __('dashboard.learn_prompt') }}</a></p>

        @if(count(Auth::user()->projects()) > 0)
        <h2>{{ __('View or manage your projects') }}</h2>

        @foreach(Auth::user()->projects() as $project)
        <p><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></p>
        @endforeach

        @elseif(count(Auth::user()->entities) > 0)

        <h2>{{ __('dashboard.entity_create_project_title') }}</h2>

        <p>{{ __('dashboard.entity_create_project_info') }}</p>

        <p><a href="{{ localized_route('projects.create', Auth::user()->entities[0]) }}">{!! __('dashboard.create_page_prompt', ['item' => __('project.singular_title_lower')]) !!}</a></p>
        @endif
    @endif

</x-app-layout>
