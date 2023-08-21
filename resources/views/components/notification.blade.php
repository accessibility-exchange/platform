<div class="stack notification">
    @if (!$read)
        <span class="notification-dot absolute top-14 left-10"></span>
    @endif
    <h3 class="h4">{{ $title }}</h3>
    <div class="content stack">
        {{ $body }}
    </div>

    {{ $contact ?? '' }}

    <x-notification.actions :notification="$notification">
        {{ $actions ?? '' }}
    </x-notification.actions>

    <p class="text-sm italic">{{ $notification->created_at->diffForHumans() }}</p>
</div>
