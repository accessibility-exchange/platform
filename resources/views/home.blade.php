<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('home.your_title', ['name' => Auth::user()->name]) }}</h1>
    </x-slot>

    {{-- <p>
        <a href="{{ localized_route('consultants.index') }}">{{ __('consultant.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p>

    <p>
        <a href="{{ localized_route('organizations.index') }}">{{ __('organization.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p>

    <p>
        <a href="{{ localized_route('entities.index') }}">{{ __('entity.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p> --}}

    @if(Auth::user()->context === 'consultant')
        @if(!Auth::user()->consultant)

        <p>{{ __('home.consultant_things_you_can_do') }}</p>

        <h2>{{ __('home.consultant_create_page_title') }}</h2>

        <p>{{ __('home.consultant_create_page_info') }}</p>

        <p><a href="{{ localized_route('consultants.create') }}">{!! __('home.create_page_prompt', ['item' => __('consultant.singular_title_lower')]) !!}</a></p>

        @else
        <p>
            <a href="{{ localized_route('consultants.show', ['consultant' => Auth::user()->consultant]) }}"><strong>{{ Auth::user()->consultant->name }}</strong>@if(Auth::user()->consultant->checkStatus('draft')) ({{ __('consultant.status_draft') }})@endif</a><br />
            <a href="{{ localized_route('consultants.edit', ['consultant' => Auth::user()->consultant]) }}">{{ __('consultant.edit_consultant_page') }}</a>
        </p>
        @endif

        <h2>{{ __('home.consultant_learn_title') }}</h2>

        <p>{{ __('home.consultant_learn_info') }}</p>

        <p><a href="{{ localized_route('resources.index') }}">{{ __('home.learn_prompt') }}</a></p>

    @elseif (Auth::user()->context === 'entity')

        <p>{{ __('home.entity_things_you_can_do') }}</p>

        @if(count(Auth::user()->entities) > 0)
        <h2>{{ __('home.entity_manage_page_title') }}</h2>

        @foreach(Auth::user()->entities as $entity)
        <p><a href="{{ localized_route('entities.show', $entity) }}"><strong>{{ $entity->name }}</strong></a></p>
        @endforeach

        @else
        <h2>{{ __('home.entity_create_page_title') }}</h2>

        <p>{{ __('home.entity_create_page_info') }}</p>

        <p>{!! __('home.create_page_prompt', ['link' => '<a href="' . localized_route('entities.create') . '"><strong>' . __('entity.singular_title_lower') . '</strong></a>']) !!}</p>
        @endif

        <h2>{{ __('home.entity_learn_title') }}</h2>

        <p>{{ __('home.entity_learn_info') }}</p>

        <p><a href="{{ localized_route('resources.index') }}">{{ __('home.learn_prompt') }}</a></p>

        @if(count(Auth::user()->entities) > 0)
        <h2>{{ __('home.entity_create_project_title') }}</h2>

        <p>{{ __('home.entity_create_project_info') }}</p>

        <p><a href="{{ localized_route('projects.create', Auth::user()->entities[0]) }}">{!! __('home.create_page_prompt', ['item' => __('project.singular_title_lower')]) !!}</a></p>
        @endif
    @endif

</x-app-layout>
