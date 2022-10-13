<x-app-wide-layout>
    <x-slot name="title">{{ __('Sign up for this engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1 class="w-full md:w-2/3">
            {{ __('Sign up for this engagement') }}
        </h1>
    </x-slot>

    <div class="stack mb-12 w-full md:w-2/3">
        <p>{{ __('Please confirm that your experience matches the following:') }}</p>

        <h2>{{ __('Location') }}</h2>

        {!! Str::markdown($engagement->matchingStrategy->location_summary) !!}

        <h2>{{ __('Disability or Deaf group') }}</h2>

        {!! Str::markdown($engagement->matchingStrategy->disability_and_deaf_group_summary) !!}

        <h2>{{ __('Other identities') }}</h2>

        {!! Str::markdown($engagement->matchingStrategy->other_identities_summary) !!}

        <form class="mt-12" action="{{ localized_route('engagements.join', $engagement) }}" method="post">
            @csrf
            <x-hearth-input name="engagement_id" type="hidden" :value="$engagement->id" />
            <button>{{ __('Confirm and sign up') }}</button>
        </form>
    </div>
</x-app-wide-layout>
