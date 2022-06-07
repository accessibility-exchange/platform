<x-app-layout>
    <x-slot name="title">{{ $organization->name }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $organization->name }}
        </h1>
        @if($organization->hasAddedDetails())
        <p class="meta">
            {{ $organization->locality }}, {{ get_region_name($organization->region, ["CA"], locale()) }}
        </p>
        @endif
    </x-slot>

    <div class="repel">
        @can('receiveNotifications')
            @if(Auth::user()->isReceivingNotificationsFor($organization))
                <form action="{{ localized_route('notification-list.remove') }}" method="post">
                    @csrf
                    <x-hearth-input type="hidden" name="notificationable_type" :value="get_class($organization)" />
                    <x-hearth-input type="hidden" name="notificationable_id" :value="$organization->id" />

                    <button class="secondary">{{ __('Remove from my notification list') }}</button>
                </form>
            @else
                <form action="{{ localized_route('notification-list.add') }}" method="post">
                    @csrf
                    <x-hearth-input type="hidden" name="notificationable_type" :value="get_class($organization)" />
                    <x-hearth-input type="hidden" name="notificationable_id" :value="$organization->id" />

                    <button class="secondary">{{ __('Add to my notification list') }}</button>
                </form>
            @endif
        @endcan
        @can('block', $organization)
            <x-block-modal :blockable="$organization" />
        @endcan
    </div>

    @can('update', $organization)
    <p><a href="{{ localized_route('organizations.edit', $organization) }}">{{ __('organization.edit_organization') }}</a></p>
    @endcan
</x-app-layout>
