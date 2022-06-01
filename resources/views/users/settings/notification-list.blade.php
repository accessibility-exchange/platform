<x-app-layout>
    <x-slot name="title">{{ __('Notification list') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Notification List') }}
        </h1>
    </x-slot>

    <p>{{ __('Organizations or projects that you want to be notified of.') }}</p>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

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

</x-app-layout>
