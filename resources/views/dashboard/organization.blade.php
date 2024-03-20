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
        @if ($memberable && $user->can('viewJoined', 'App\Models\Engagement'))
            @if (
                $memberable->isParticipant() ||
                    $memberable->isConnector() ||
                    $memberable->engagements()->count() ||
                    $memberable->connectingEngagements()->count())
                <li>
                    <a href="{{ localized_route('engagements.joined') }}">{{ __('Engagements I’ve joined') }}</a>
                </li>
            @endif
        @endif
        @if ($memberable && $user->can('viewAny', 'App\Models\Project'))
            <li>
                <a href="{{ localized_route('projects.my-running-projects') }}">{{ __('Projects I’m running') }}</a>
            </li>
        @endif
        <li>
            <a href="{{ localized_route('dashboard.trainings') }}">{{ __('My trainings') }}</a>
        </li>
    </x-quick-links>
    <div class="border-divider mb-12 mt-14 border-x-0 border-b-0 border-t-3 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
