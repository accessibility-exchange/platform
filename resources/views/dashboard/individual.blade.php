<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        @if ($user->individual->isConnector() || $user->individual->isConsultant())
            <li>
                @if ($user->individual->checkStatus('published'))
                    <a href="{{ localized_route('individuals.show', $user->individual) }}">{{ __('My public page') }}</a>
                @else
                    <a
                        href="{{ localized_route('individuals.edit', $user->individual) }}">{{ __('Edit my public page') }}</a>
                @endif
            </li>
        @endif
        @if (!$user->oriented_at)
            <li>
                <a href="{{ orientation_link($user->context) }}">{{ __('Sign up for an orientation session') }}</a>
            </li>
        @endif
        @can('viewJoined', 'App\Models\Engagement')
            @if (
                $user->individual->isParticipant() ||
                    $user->individual->isConnector() ||
                    $user->individual->engagements()->count() ||
                    $user->individual->connectingEngagements()->count())
                <li>
                    <a href="{{ localized_route('engagements.joined') }}">{{ __('Engagements I’ve joined') }}</a>
                </li>
            @endif
        @endcan
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
