<x-app-wide-tabbed-layout>
    <x-slot name="title">{{ __('Notifications') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1 id="notifications">
            {{ __('Notifications') }}
        </h1>
    </x-slot>

    @if ($user->context === 'individual')
        <nav class="full bg-white shadow-md" aria-labelledby="notifications">
            <div class="center center:wide">
                <ul class="-mt-4 flex gap-6" role="list">
                    <li class="w-1/2">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('settings.edit-notification-preferences')"
                            :active="request()->localizedRouteIs('settings.edit-notification-preferences')">{{ __('Manage notifications') }}</x-nav-link>
                    </li>
                    <li class="w-1/2">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('notification-list.show')"
                            :active="request()->localizedRouteIs('notification-list.show')">{{ __('Notification list') }}</x-nav-link>
                    </li>
                </ul>
            </div>
        </nav>
    @endif

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('Manage my notifications') }}</h2>
    <p>{{ __('The Accessibility Exchange will send you notifications, based on what you chose to be notified of here.') }}
    </p>

    @include('settings.notifications.' . $user->context)
</x-app-wide-tabbed-layout>
