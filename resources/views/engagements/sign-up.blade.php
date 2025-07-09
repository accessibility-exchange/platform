<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Sign up for this engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('engagements.index') }}">{{ __('Engagements') }}</a></li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1 class="w-full md:w-2/3">
            {{ __('Sign up for this engagement') }}
        </h1>
        <x-interpretation name="{{ __('Sign up for this engagement', [], 'en') }}" />
    </x-slot>

    <div class="stack mb-12 w-full md:w-2/3">
        <h2>{{ __('Confirm lived experiences') }}</h2>

        <p>{{ __('Please confirm that your experience matches the following:') }}</p>

        <h3>{{ __('Location') }}</h3>
        <x-interpretation name="{{ __('Location', [], 'en') }}" />

        <x-array-list-view :data="$engagement->matchingStrategy->location_summary" />

        <h3>{{ __('Disability or Deaf group') }}</h3>
        <x-interpretation name="{{ __('Disability or Deaf group', [], 'en') }}" />

        <x-array-list-view :data="$engagement->matchingStrategy->disability_and_deaf_group_summary" />

        <h3>{{ __('Other identities') }}</h3>
        <x-interpretation name="{{ __('Other identities', [], 'en') }}" />

        <x-array-list-view :data="$engagement->matchingStrategy->other_identities_summary" />

        @if (!$engagement->paid)
            <h2>{{ __('This is a volunteer engagement') }}</h2>
            <p>{{ __('You will not receive payment for participating in this engagement.') }}</p>
        @endif

        <form class="mt-12" action="{{ localized_route('engagements.join', $engagement) }}" method="post">
            @csrf
            <button>{{ __('Confirm and sign up') }}</button>
        </form>
    </div>
</x-app-layout>
