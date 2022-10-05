<div class="with-sidebar">
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        <h2>{{ __('Quick Links') }}</h2>
        <ul class="link-list" role="list">
            @if ($user->individual->isConnector() || $user->individual->isConsultant())
                <li>
                    <a
                        href="{{ $user->individual->checkStatus('draft') ? localized_route('individuals.edit', $user->individual) : localized_route('individuals.show', $user->individual) }}">Public
                        page</a>
                </li>
                <li>
                    <a
                        href="{{ $user->individual->isParticipant() ? localized_route('projects.my-contracted-projects') : localized_route('projects.my-projects') }}">{{ __('Projects I’m contracted for') }}</a>
                </li>
            @endif
            @if ($user->individual->isParticipant())
                <li>
                    <a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects I’m participating in') }}</a>
                </li>
            @endif

        </ul>
    </div>

    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
