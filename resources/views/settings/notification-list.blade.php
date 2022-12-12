<x-app-layout header-class="header--tabbed" page-width="wide">
    <x-slot name="title">{{ __('Notification list') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
                <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
                <li><a
                        href="{{ localized_route('settings.edit-notification-preferences') }}">{{ __('Notifications') }}</a>
                </li>
            </ol>
            <h1>
                {{ __('Notification List') }}
            </h1>
        </div>
    </x-slot>

    <nav class="nav--tabbed" aria-labelledby="notifications">
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

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Organizations or projects that you want to be notified about.') }}</p>

    <h2 id="regulated-organizations">{{ __('Regulated organizations') }}</h2>

    <div role="region" aria-labelledby="regulated-organizations" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th></th>
                </tr>
            </thead>
            @forelse (Auth::user()->regulatedOrganizationsForNotification as $notificationable)
                <tr>
                    <td>{{ $notificationable->name }}</td>
                    <td>
                        <form action="{{ localized_route('notification-list.remove') }}" method="POST">
                            @csrf
                            <x-hearth-input name="notificationable_type" type="hidden" :value="get_class($notificationable)" />
                            <x-hearth-input name="notificationable_id" type="hidden" :value="$notificationable->id" />
                            <button class="secondary"
                                :aria-label="__('Remove :notificationable', ['notificationable' => $notificationable - > name])">
                                {{ __('Remove') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>

    <h2 id="community-organizations">{{ __('Community organizations') }}</h2>

    <div role="region" aria-labelledby="community-organizations" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th></th>
                </tr>
            </thead>
            @forelse (Auth::user()->organizationsForNotification as $notificationable)
                <tr>
                    <td>{{ $notificationable->name }}</td>
                    <td>
                        <form action="{{ localized_route('notification-list.remove') }}" method="POST">
                            @csrf
                            <x-hearth-input name="notificationable_type" type="hidden" :value="get_class($notificationable)" />
                            <x-hearth-input name="notificationable_id" type="hidden" :value="$notificationable->id" />
                            <button class="secondary"
                                aria-label="{{ __('Remove :notificationable', ['notificationable' => $notificationable->name]) }}">
                                {{ __('Remove') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>
