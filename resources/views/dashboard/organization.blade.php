<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        @can('update', $memberable)
            @if ($user->organization->checkStatus('published'))
                <li>
                    <a href="{{ localized_route('organizations.show', $memberable) }}">{{ __('My organization’s page') }}</a>
                </li>
            @else
                <li>
                    <a
                        href="{{ localized_route('organizations.edit', $memberable) }}">{{ __('Edit my organization’s page') }}</a>
                </li>
            @endif
        @else
            @can('view', $memberable)
                @if ($user->organization->checkStatus('published'))
                    <li>
                        <a href="{{ localized_route('organizations.show', $memberable) }}">{{ __('My organization’s page') }}</a>
                    </li>
                @endif
            @endcan
        @endcan
        @if (!$user->organization?->oriented_at)
            <li>
                <a href="{{ orientation_link($user->context) }}">{{ __('Sign up for an orientation session') }}</a>
            </li>
        @endif
        @can('viewAny', App\Models\Project::class)
            @if ($memberable->isConnector() || $memberable->isConsultant())
                <li>
                    <a href="{{ localized_route('projects.my-projects') }}">{{ __('Involved as a Community Connector') }}</a>
                </li>
            @endif
            @if ($memberable->isParticipant())
                <li>
                    <a
                        href="{{ !$memberable->isConnector() && !$memberable->isConsultant() ? localized_route('projects.my-projects') : localized_route('projects.my-participating-projects') }}">{{ __('Involved as a Consultation Participant') }}</a>
                </li>
            @endif
            <li>
                <a href="{{ localized_route('projects.my-running-projects') }}">{{ __('Projects I’m running') }}</a>
            </li>
        @endcan
    </x-quick-links>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
