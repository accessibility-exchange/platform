<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        <li>
            <a href="{{ localized_route('admin.manage-accounts') }}">{{ __('Manage accounts') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('admin.estimates-and-agreements') }}">{{ __('Estimates and agreements') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.resources.access-supports.index') }}">{{ __('Access supports') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.resources.content-types.index') }}">{{ __('Content types') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.resources.identities.index') }}">{{ __('Identities') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.resources.pages.index') }}">{{ __('Pages') }}</a>
        </li>
        <li>
            <a
                href="{{ route('filament.admin.resources.resource-collections.index') }}">{{ __('Resource collections') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.resources.resources.index') }}">{{ __('Resources') }}</a>
        </li>
        <li>
            <a
                href="{{ route('filament.admin.resources.interpretations.index') }}">{{ __('Sign language interpretations') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.resources.topics.index') }}">{{ __('Topics') }}</a>
        </li>
        <li>
            <a href="{{ route('filament.admin.pages.settings') }}">{{ __('Website settings') }}</a>
        </li>
    </x-quick-links>
    <div class="border-divider mb-12 border-x-0 border-b-0 border-t-3 border-solid pt-6 md:mt-14">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
