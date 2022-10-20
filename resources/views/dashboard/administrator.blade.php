<div class="with-sidebar">
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        <h2>{{ __('Quick Links') }}</h2>
        <ul class="link-list" role="list">
            <li>
                <a href="{{ localized_route('admin.manage-users') }}">{{ __('Manage users') }}</a>
            </li>
            <li>
                <a
                    href="{{ localized_route('admin.estimates-and-agreements') }}">{{ __('Estimates and agreements') }}</a>
            </li>
        </ul>
    </div>

    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
