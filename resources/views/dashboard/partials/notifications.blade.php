<div class="stack">
    <div class="flex items-center gap-5">
        <x-heroicon-o-bell class="h-12 w-12 text-magenta-3" role="presentation" aria-hidden="true" />
        <h2 class="mt-0">{{ __('Notifications') }}</h2>
        @if ($notifications->count())
            <span
                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-magenta-3">{{ $notifications->count() }}</span>
        @endif
    </div>
    @forelse($notifications as $notification)
        <x-dynamic-component :component="'notification.' . Str::kebab(class_basename($notification->type))" :notification="$notification" />
    @empty
        <div class="box">{{ __('At present, you have no new notifications.') }}</div>
    @endforelse
    <p><a class="with-icon" href="{{ localized_route('dashboard.notifications') }}">{{ __('All notifications') }}
            @if ($notifications->count())
                ({{ __(':count more unread', ['count' => $notifications->count()]) }})
            @endif
            <x-heroicon-s-chevron-right class="h-5 w-5" role="presentation" aria-hidden="true" />
        </a></p>
</div>
