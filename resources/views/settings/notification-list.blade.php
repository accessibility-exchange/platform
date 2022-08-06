<x-app-wide-layout>
    <x-slot name="title">{{ __('Notification list') }}</x-slot>
    <x-slot name="header">
        <div class="full bg-white -mt-12 py-12 border-b-grey-3 border-solid border-b border-x-0 border-t-0">
            <div class="center center:wide">
                <ol class="breadcrumbs" role="list">
                    <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
                    <li><a href="{{ localized_route('settings.edit-notification-preferences') }}">{{ __('Notifications') }}</a></li>
                </ol>
                <h1>
                    {{ __('Notification List') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <nav aria-labelledby="notifications" class="full bg-white mb-12 shadow-md">
        <div class="center center:wide">
            <ul role="list" class="flex gap-6 -mt-4">
                <li class="w-1/2">
                    <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('settings.edit-notification-preferences')" :active="request()->localizedRouteIs('settings.edit-notification-preferences')">{{ __('Manage notifications') }}</x-nav-link>
                </li>
                <li class="w-1/2">
                    <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('notification-list.show')" :active="request()->localizedRouteIs('notification-list.show')">{{ __('Notification list') }}</x-nav-link>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Regulated or community organizations that you want to be notified about.') }}</p>

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
                            <x-hearth-input type="hidden" name="notificationable_type" :value="get_class($notificationable)" />
                            <x-hearth-input type="hidden" name="notificationable_id" :value="$notificationable->id" />
                            <button class="secondary" :aria-label="__('Remove :notificationable', ['notificationable' => $notificationable->name])">
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
                            <x-hearth-input type="hidden" name="notificationable_type" :value="get_class($notificationable)" />
                            <x-hearth-input type="hidden" name="notificationable_id" :value="$notificationable->id" />
                            <button class="secondary" aria-label="{{ __('Remove :notificationable', ['notificationable' => $notificationable->name]) }}">
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

</x-app-wide-layout>
