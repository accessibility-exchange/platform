<div class="stack">
    <div class="flex items-center gap-5">
        @svg('heroicon-o-bell', 'icon--2xl text-magenta-3')

        <h2 class="mt-0">{{ __('Notifications') }}</h2>
        @if ($notifications->count())
            <span
                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-magenta-3">{{ $notifications->count() }}</span>
        @endif
    </div>
    @forelse($notifications as $notification)
        <x-dynamic-component :component="'notification.' . Str::kebab(class_basename($notification->type))" :notification="$notification" />
    @empty
        <div class="box">{{ __('At present, you have no unread notifications.') }}</div>
    @endforelse
    <p><a class="with-icon" href="{{ localized_route('dashboard.notifications') }}">{{ __('All notifications') }}
            @if ($notifications->count() > 2)
                ({{ __(':count more unread', ['count' => $notifications->count() - 2]) }})
            @endif
            @svg('heroicon-s-chevron-right')
        </a></p>
</div>
