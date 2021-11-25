<div class="columns">
    <div class="column flow">
        <div class="box flow">
            <h2>{{ __('Getting started') }}</h2>
            @if(!$currentUser->consultant)
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create your community member page') }}</x-slot>
                <div class="flow">
                    <p>{{ __('Once you create your page, entities can find you and ask you to consult on their projects.') }}</p>
                    <p><a class="button" href="{{ localized_route('consultants.create') }}">{{ __('Create your page') }}</a></p>
                </div>
            </x-expander>
            @else
            <x-expander level="3">
                <x-slot name="summary">{{ __('Find entities to follow') }}</x-slot>
                <div class="flow">
                    <p>{{ __('Once you follow some entities that you’re interested in, you will be notified whenever they begin a community consultation process.') }}</p>
                    <p><a class="button" href="{{ localized_route('entities.index') }}">{{ __('Find entities') }}</a></p>
                </div>
            </x-expander>
            @endif
            <x-expander level="3">
                <x-slot name="summary">{{ __('Learn about participating in consultations') }}</x-slot>
                <div class="flow">
                    <p>{{ __('Find resources about the accessibility planning process and how you can participate in it.') }}</p>
                    <p><a class="button" href="{{ localized_route('collections.index') }}">{{ __('Explore the resource hub') }}</a></p>
                </div>
            </x-expander>
        </div>

        @if($currentUser->consultant)
        <div class="box flow">
            <h2>{{ __('My page') }}</h2>
            <p>
                <a href="{{ localized_route('consultants.show', ['consultant' => $currentUser->consultant]) }}"><strong>{{ __('Visit my page') }}</strong>@if($currentUser->consultant->checkStatus('draft')) ({{ __('draft') }})@endif</a><br />
                <a href="{{ localized_route('consultants.edit', ['consultant' => $currentUser->consultant]) }}">{{ __('Edit my page') }}</a>
            </p>
        </div>

        <div class="box">
            <h2>{{ __('Notifications') }} <span class="badge">0</span></h2>
        </div>

        <div class="box">
            <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
        </div>
        @endif
    </div>

    <div class="column flow">
        @if($currentUser->consultant)
        <div class="box">
            <h2>{{ __('My active projects') }}</h2>
            @if(count($currentUser->consultant->projects) > 0)
            {{-- TODO: Display project cards. --}}
            @else
            <p>{!! __('You have no active projects right now. To find a project to work on, :action and express your interest in ones that you want to work on.', ['action' => '<a href="' . localized_route('projects.index') . '">' . __('browse through our list of projects') . '</a>']) !!}</p>
            @endif
        </div>
        @else
        <div class="box">
            <h2>{{ __('Notifications') }} <span class="badge">0</span></h2>
        </div>

        <div class="box">
            <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
        </div>
        @endif
    </div>
</div>
