<x-slot name="header">
    <ol class="breadcrumbs" role="list">
        <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
    </ol>
    <h1>
        {{ __('Accessibility Consultant') }}
    </h1>
</x-slot>

<div class="stack">
    <div role="alert" x-data="{ visible: false }" @add-flash-message.window="visible = true"
        @clear-flash-message.window="visible = false"
        @remove-flash-message.window="setTimeout(() => visible = false, 5000)">
        <div x-show="visible" x-transition:leave.duration.500ms>
            @if (session()->has('message'))
                <x-hearth-alert type="success">
                    {!! Str::markdown(session('message')) !!}
                </x-hearth-alert>
            @endif
        </div>
    </div>

    <h2>{{ __('Find an Accessibility Consultant') }}</h2>

    <p>{{ __('If you are seeking an Accessibility Consultant for this engagement, there are a few ways to find one:') }}
    </p>

    <h3>{{ __('Show that you are looking for an Accessibility Consultant') }}</h3>

    <p>{!! __(
        'This will show Accessibility Consultants on the :browse page that you are looking, and that they are welcome to reach out.',
        [
            'browse' => '<a href="' . localized_route('projects.index') . '">' . __('browse projects') . '</a>',
        ],
    ) !!}
    </p>

    <div class="field">
        <x-hearth-checkbox name="seeking_accessibility_consultant" wire:model="seeking_accessibility_consultant"
            wire:click="updateStatus" />
        <x-hearth-label for="seeking_accessibility_consultant">
            {{ __('I am currently seeing an Accessibility Consultant for this engagement') }}</x-hearth-label>
    </div>

    <hr class="border-t-1 mt-16 mb-12 border-x-0 border-b-0 border-solid border-t-blue-7" />

    <h3>{{ __('Browse for an Accessibility Consultant') }}</h3>

    <p>{{ __('Go through our listings of Accessibility Consultants on this website.') }}</p>

    <p>
        <a class="cta secondary" href="#TODO">{{ __('Browse Accessibility Consultants') }}</a>
    </p>

    <hr class="mt-16 mb-12 border-x-0 border-t-3 border-b-0 border-solid border-t-blue-7" />

    <h2>{{ __('Manage Accessibility Consultant') }}</h2>

    <p>{{ __('Once you have hired an Accessibility Consultant, please add them here. This will give them access to your engagement details.') }}
    </p>

    <p>
        <a class="cta secondary" href="#TODO">
            <x-heroicon-o-plus-circle role="presentation" aria-hidden="true" />
            {{ __('Add Accessibility Consultant') }}
        </a>
    </p>

    <hr class="mt-16 mb-12 border-x-0 border-t-3 border-b-0 border-solid border-t-blue-7" />

    <p>
        <a class="cta secondary" href="{{ localized_route('engagements.manage', $engagement) }}">
            <x-heroicon-o-arrow-left role="presentation" aria-hidden="true" /> {{ __('Back') }}
        </a>
    </p>
</div>
