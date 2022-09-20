@props(['notification'])
<div class="actions flex gap-5">
    {{ $slot ?? '' }}

    @if (!$notification->read_at)
        <livewire:mark-notification-as-read :notification="$notification" />
    @endif
</div>
