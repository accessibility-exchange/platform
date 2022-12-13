<x-app-layout header-class="header--tabbed" page-width="wide">
    <x-slot name="title">
        {{ __('Notifications') }}
    </x-slot>
    <x-slot name="header">
        <div class="center center:wide stack">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                @if (request()->localizedRouteIs('dashboard.notifications-all'))
                    <li><a href="{{ localized_route('dashboard.notifications') }}">{{ __('Notifications') }}</a></li>
                @endif
            </ol>
            <h1 id="notifications">
                {{ __('Notifications') }}
            </h1>
        </div>
    </x-slot>

    @section('navigation')
        <nav class="nav--tabbed" aria-labelledby="{{ __('notifications navigation') }}">
            <div class="center center:wide">
                <ul class="-mt-4 flex gap-6" role="list">
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center gap-2 border-t-0"
                            :href="localized_route('dashboard.notifications')" :active="request()->localizedRouteIs('dashboard.notifications')">
                            {{ __('Unread') }}@if ($unreadCount)
                                <span class="notification-dot h-6 w-6">{{ $unreadCount }}</span>
                            @endif
                        </x-nav-link>
                    </li>
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('dashboard.notifications-all')"
                            :active="request()->localizedRouteIs('dashboard.notifications-all')">
                            {{ __('All') }}
                        </x-nav-link>
                    </li>
                </ul>
            </div>
        </nav>
    @show

    <div class="mx-auto max-w-2xl space-y-6">
        @forelse($notifications as $notification)
            <x-dynamic-component :component="'notification.' . Str::kebab(class_basename($notification->type))" :notification="$notification" />
        @empty
            <p>{{ __('At present, you have no unread notifications.') }}</p>
        @endforelse
        {{ $notifications->links() }}
    </div>

</x-app-layout>
