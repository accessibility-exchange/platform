<x-app-wide-layout>
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
    <div class="frame">
        {{ $user->introduction() }}
    </div>

    <div class="center repel">
        <a class="cta secondary" href="{{ $skipTo }}">{{ __('Skip for now') }}</a>
        <form class="width:full" action="{{ localized_route('users.update-introduction-status') }}" method="post">
            @method('put')
            @csrf

            <input name="finished_introduction" type="hidden" value="1" />
            <button>{{ __('Continue') }}</button>
        </form>
    </div>
</x-app-wide-layout>
