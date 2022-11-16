<x-app-medium-layout>
    <x-slot name="title">{{ __('Add participant') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
            </li>
            <li><a
                    href="@can('update', $engagement){{ localized_route('engagements.manage', $engagement) }}@else{{ localized_route('engagements.show', $engagement) }}@endcan">{{ $engagement->name }}</a>
            </li>
            <li>
                <a
                    href="{{ localized_route('engagements.manage-participants', $engagement) }}">{{ __('Manage participants') }}</a>
            </li>
        </ol>
        <h1>
            {{ __('Add participant') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('engagements.invite-participant', $engagement) }}" method="post"
        novalidate>
        @csrf

        <div class="field @error('email') field--error @enderror">
            <x-hearth-label for="email">{{ __('Email address') }}</x-hearth-label>
            <x-hearth-hint for="email">{{ __('This is the email your invitation will be sent to.') }}
            </x-hearth-hint>
            <x-hearth-input name="email" type="email" :value="old('email')" hinted />
            <x-hearth-error for="email" />
        </div>

        <hr class="divider--thick" />

        <div class="flex flex-row gap-6">
            <a class="cta secondary" href="{{ localized_route('engagements.manage-participants', $engagement) }}">
                @svg('heroicon-o-arrow-left') {{ __('Cancel') }}
            </a>
            <button>{{ __('Send invitation') }}</button>
        </div>
    </form>
</x-app-medium-layout>
