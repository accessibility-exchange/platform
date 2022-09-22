<div class="stack relative bg-white px-20 py-6 shadow-md">
    @if (!$read)
        <span class="absolute top-14 left-10 block h-5 w-5 rounded-full bg-magenta-3"></span>
    @endif
    <h3 class="h4">{{ $title }}</h3>
    <div class="content stack">
        {!! Str::markdown($body) !!}
    </div>

    <x-notification.actions :notification="$notification">
        {{ $actions ?? '' }}
    </x-notification.actions>

    <p class="text-sm italic">{{ $notification->created_at->diffForHumans() }}</p>
</div>
