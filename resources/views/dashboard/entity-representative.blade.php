<div class="columns">
    <div class="column flow">
        <div class="box flow">
            <h2>{{ __('Getting started') }}</h2>
            @if(!$currentUser->entity())
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create your entity page') }}</x-slot>
                <div class="flow">
                    <p>{{ __('Share more about your organization so that community members can get to know you.') }}</p>
                    <p><a class="button" href="{{ localized_route('entities.create') }}">{{ __('Create your page') }}</a></p>
                </div>
            </x-expander>
            @else
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create a project page') }}</x-slot>
                <div class="flow">
                    <p>{{ __('Create a new project page so that community members can begin to express their interest in working with you.') }}</p>
                    <p><a class="button" href="{{ localized_route('projects.create', $currentUser->entity()) }}">{{ __('Create project page') }}</a></p>
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

        @if($currentUser->entity())
        <div class="box flow">
            <h2>{{ __('My entity page') }}</h2>
            <p>
                <a href="{{ localized_route('entities.show', $currentUser->entity()) }}"><strong>{{ __('Visit my entity page') }}</strong><br />
                <a href="{{ localized_route('entities.edit', $currentUser->entity()) }}">{{ __('Edit my entity page') }}</a>
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
        @if($currentUser->entity())
        <div class="box flow">
            <h2>{{ __('My active projects') }}</h2>
            @if(count($currentUser->entity()->projects) > 0)
                @foreach($currentUser->entity()->projects as $project)
                <x-project-card :project="$project" />
                @endforeach
            <p><a href="{{ localized_route('users.show_my_projects') }}">{{ __('Show all my projects') }}</a></p>
            @else
            <p>{!! __('You have no active projects right now. :action', ['action' => '<strong><a href="' . localized_route('projects.create', $currentUser->entity()) . '">' . __('Create your first project.') . '</a></strong>']) !!}</p>
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
