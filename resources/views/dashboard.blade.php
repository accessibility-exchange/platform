<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            <small>{{ Auth::user()->name }}@if(count(Auth::user()->entities) > 0), {{ Auth::user()->entities->first()->name }}@endif</small><br />
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
        <div class="columns">
            <div class="column flow">
                <div class="box flow">
                    <h2>{{ __('Getting started') }}</h2>
                    @if(!count(Auth::user()->entities) > 0)
                    <x-expander level="3">
                        <x-slot name="summary">{{ __('Create your entity page') }}</x-slot>
                        <div class="flow">
                            <p>{{ __('Share more about your organization so that consultants can get to know you.') }}</p>
                            <p><a class="button" href="{{ localized_route('entities.create') }}">{{ __('Create your page') }}</a></p>
                        </div>
                    </x-expander>
                    @else
                    <x-expander level="3">
                        <x-slot name="summary">{{ __('Create a project page') }}</x-slot>
                        <div class="flow">
                            <p>{{ __('Create a new project page so that consultants can begin to express their interest in working with you.') }}</p>
                            <p><a class="button" href="{{ localized_route('projects.create', Auth::user()->entities->first()) }}">{{ __('Create project page') }}</a></p>
                        </div>
                    </x-expander>
                    @endif
                    <x-expander level="3">
                        <x-slot name="summary">{{ __('Learn about engaging the disability community') }}</x-slot>
                        <div class="flow">
                            <p>{{ __('Browse through our resources and learn more.') }}</p>
                            <p><a class="button" href="{{ localized_route('collections.index') }}">{{ __('Explore the resource hub') }}</a></p>
                        </div>
                    </x-expander>
                </div>

                @if(count(Auth::user()->entities) > 0)
                <div class="box flow">
                    <h2>{{ __('My entity page') }}</h2>
                    <p>
                        <a href="{{ localized_route('entities.show', Auth::user()->entities->first()) }}"><strong>{{ __('Visit my entity page') }}</strong><br />
                        <a href="{{ localized_route('entities.edit', Auth::user()->entities->first()) }}">{{ __('Edit my entity page') }}</a>
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
                    @if(Auth::user()->entities->first() && count(Auth::user()->entities->first()->projects) > 0)
                    {{-- TODO: Display project cards. --}}
                    @else
                    <p>{!! __('You have no active projects right now. :action', ['action' => '<strong><a href="' . localized_route('projects.create', Auth::user()->entities->first()) . '">' . __('Create your first project.') . '</a></strong>']) !!}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

</x-app-wide-layout>
