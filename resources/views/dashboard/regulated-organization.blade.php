<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        @can('update', $memberable)
            @if ($user->regulatedOrganization->checkStatus('published'))
                <li>
                    <a
                        href="{{ localized_route('regulated-organizations.show', $memberable) }}">{{ __('My organization’s page') }}</a>
                </li>
            @else
                <li>
                    <a
                        href="{{ localized_route('regulated-organizations.edit', $memberable) }}">{{ __('Edit my organization’s page') }}</a>
                </li>
            @endif
        @else
            @can('view', $memberable)
                @if ($user->regulatedOrganization->checkStatus('published'))
                    <li>
                        <a
                            href="{{ localized_route('regulated-organizations.show', $memberable) }}">{{ __('My organization’s page') }}</a>
                    </li>
                @endif
            @endcan
        @endcan
        @if (!$user->oriented_at)
            <li>
                <a
                    href="https://share.hsforms.com/1gGf9TjhaQ0uaqcnyJfSDlwdfpez">{{ __('Sign up for an orientation session') }}</a>
            </li>
        @endif
        @can('viewAny', App\Models\Project::class)
            <li>
                <a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects I’m running') }}</a>
            </li>
        @endcan
    </x-quick-links>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
