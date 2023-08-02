<div class="stack notification">
    @if (!$read)
        <span class="notification-dot absolute left-10 top-14"></span>
    @endif
    <h3 class="h4">{{ $title }}</h3>
    <x-interpretation name="{{ $interpretation }}" namespace="notification_message" />
    <div class="content stack">
        {{ $body }}
    </div>

    {{ $contact ?? '' }}

    <x-notification.actions :notification="$notification">
        {{ $actions ?? '' }}
    </x-notification.actions>

    <p class="text-sm italic">{{ $notification->created_at->diffForHumans() }}</p>
</div>
