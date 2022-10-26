<div class="with-sidebar">
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        <h2>{{ __('Quick Links') }}</h2>
        <ul class="link-list" role="list">
            @can('view', $memberable)
                <li>
                    <a
                        href="{{ $memberable->checkStatus('draft') && $user->can('edit', $memberable) ? localized_route('regulated-organizations.edit', $memberable) : localized_route('regulated-organizations.show', $memberable) }}">{{ __('My organization’s page') }}</a>
                </li>
            @endcan
            @can('viewAny', App\Models\Project::class)
                <li>
                    <a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects I’m running') }}</a>
                </li>
            @endcan
        </ul>
    </div>

    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
