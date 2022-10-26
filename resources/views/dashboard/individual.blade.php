<div class="with-sidebar">
    <x-quick-links>
        @if ($user->individual->isConnector() || $user->individual->isConsultant())
            <li>
                <a
                    href="{{ $user->individual->checkStatus('draft') && $user->can('edit', $user->individual) ? localized_route('individuals.edit', $user->individual) : localized_route('individuals.show', $user->individual) }}">{{ __('Public page') }}</a>
            </li>
            @can('viewAny', App\Models\Project::class)
                <li>
                    <a
                        href="{{ $user->individual->isParticipant() ? localized_route('projects.my-contracted-projects') : localized_route('projects.my-projects') }}">{{ __('Projects I’m contracted for') }}</a>
                </li>
            @endcan
        @endif
        @can('viewAny', App\Models\Project::class)
            @if ($user->individual->isParticipant())
                <li>
                    <a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects I’m participating in') }}</a>
                </li>
            @endif
        @endcan
    </x-quick-links>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
