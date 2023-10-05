<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>
    </x-slot>

    <h2>
        {{ __('Introduction video') }}
    </h2>

    <!-- Video -->
    @if (array_key_exists(locale(), $user->introduction()))
        <div class="frame">
            <div class="stack w-full" x-data="vimeoPlayer({
                url: '{{ $user->introduction()[locale()] }}',
                byline: false,
                dnt: true,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" @ended="player().setCurrentTime(0)">
            </div>
        </div>
    @else
        <div class="frame">
            {{ __('Introduction video') }}
        </div>
    @endif

    <div class="center repel">
        <a class="cta secondary" href="{{ $skipTo }}">{{ __('Skip for now') }}</a>
        <form class="width:full" action="{{ localized_route('users.update-introduction-status') }}" method="post">
            @method('put')
            @csrf

            <input name="finished_introduction" type="hidden" value="1" />
            <button>{{ __('Continue') }}</button>
        </form>
    </div>
</x-app-layout>
