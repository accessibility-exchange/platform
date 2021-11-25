<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            <small>{{ Auth::user()->name }}</small><br />
            {{ __('My dashboard') }}
        </h1>
    </x-slot>

    @if(Auth::user()->context === 'consultant')
        <div class="columns">
            <div class="column flow">
                <div class="box flow">
                    <h2>{{ __('Getting started') }}</h2>
                    @if(!Auth::user()->consultant)
                    <x-expander level="3">
                        <x-slot name="summary">{{ __('Create your community member page') }}</x-slot>
                        <div class="flow">
                            <p>{{ __('Once you create your page, entities can find you and ask you to consult on their projects.') }}</p>
                            <p><a class="button" href="{{ localized_route('consultants.create') }}">{{ __('Create your page') }}</a></p>
                        </div>
                    </x-expander>
                    @endif
                    <x-expander level="3">
                        <x-slot name="summary">{{ __('Find entities to follow') }}</x-slot>
                        <div class="flow">
                            <p>{{ __('Once you follow some entities that you’re interested in, you will be notified whenever they begin a community consultation process.') }}</p>
                            <p><a class="button" href="{{ localized_route('entities.index') }}">{{ __('Find entities') }}</a></p>
                        </div>
                    </x-expander>
                    <x-expander level="3">
                        <x-slot name="summary">{{ __('Learn about participating in consultations') }}</x-slot>
                        <div class="flow">
                            <p>{{ __('Find resources about the accessibility planning process and how you can participate in it.') }}</p>
                            <p><a class="button" href="{{ localized_route('collections.index') }}">{{ __('Explore the resource hub') }}</a></p>
                        </div>
                    </x-expander>
                </div>

                @if(Auth::user()->consultant)
                <div class="box flow">
                    <h2>{{ __('My page') }}</h2>
                    <p>
                        <a href="{{ localized_route('consultants.show', ['consultant' => Auth::user()->consultant]) }}"><strong>{{ __('Visit my page') }}</strong>@if(Auth::user()->consultant->checkStatus('draft')) ({{ __('draft') }})@endif</a><br />
                        <a href="{{ localized_route('consultants.edit', ['consultant' => Auth::user()->consultant]) }}">{{ __('Edit my page') }}</a>
                    </p>
                </div>
                @endif

                <div class="box">
                    <h2>{{ __('Notifications') }} <span class="badge">0</span></h2>
                </div>

                <div class="box">
                    <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
                </div>
            </div>

            <div class="column flow">
                <div class="box">
                    <h2>{{ __('My active projects') }}</h2>
                    @if(Auth::user()->consultant && count(Auth::user()->consultant->projects) > 0)
                    {{-- TODO: Display project cards. --}}
                    @else
                    <p>{!! __('You have no active projects right now. To find a project to work on, :action and express your interest in ones that you want to work on.', ['action' => '<a href="' . localized_route('projects.index') . '">' . __('browse through our list of projects') . '</a>']) !!}</p>
                    @endif
                </div>
            </div>
        </div>

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

        <p><a href="{{ localized_route('projects.create', Auth::user()->entities[0]) }}">{!! __('dashboard.create_page_prompt', ['item' => __('project')]) !!}</a></p>
        @endif
    @endif

</x-app-wide-layout>
