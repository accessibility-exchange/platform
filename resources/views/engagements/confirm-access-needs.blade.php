<x-app-wide-layout>
    <x-slot name="title">{{ __('Confirm access needs') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1 class="w-full md:w-2/3">
            {{ __('Confirm access needs') }}
        </h1>
    </x-slot>

    <div class="stack mb-12 w-full md:w-2/3">
        <p>{{ __('You have successfully signed up, and your name and contact information have been shared with :projectable.', ['projectable' => $project->projectable->name]) }}
        </p>

        <h2>{{ __('What’s been shared anonymously') }}</h2>

        {!! Str::markdown(
            __(
                'The following access needs have been shared with :projectable **anonymously**. They will **not** know it is you who requested them.',
                ['projectable' => $project->projectable->name],
            ),
        ) !!}

        <ul class="my-8 space-y-6" role="list">
            @forelse(Auth::user()->individual->accessSupports->where('anonymizable', true) as $support)
                <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">{{ $support->name }}</li>
            @empty
                <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">
                    {{ __('No access needs found.') }}</li>
            @endforelse
        </ul>

        @if (Auth::user()->individual->accessSupports->where('anonymizable', false)->count())
            <div class="box stack">
                <h2>{{ __('What we need your permission to share') }}</h2>
                <p>{{ __('In order for :projectable to meet the following access needs, they will need to know who requested them. Do you give us permission to share that it was you who requested the following access needs?', ['projectable' => $project->projectable->name]) }}
                </p>
                <ul class="my-8 space-y-6" role="list">
                    @foreach (Auth::user()->individual->accessSupports->where('anonymizable', false) as $support)
                        <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">
                            {{ $support->name }}
                        </li>
                    @endforeach
                </ul>
                <form class="mt-12 flex flex-row gap-6"
                    action="{{ localized_route('engagements.store-access-needs-permissions', $engagement) }}"
                    method="post">
                    @csrf
                    <button class="secondary" name="share_access_needs" value="1">
                        @svg('heroicon-s-check') {{ __('Yes') }}
                    </button>
                    <button class="secondary" name="share_access_needs" value="0">
                        @svg('heroicon-s-x') {{ __('No') }}
                    </button>
                </form>
            </div>
        @endif

        {!! Str::markdown(
            __('Please [update your access needs](:access_needs) if they have changed.', [
                'access_needs' => localized_route('settings.edit-access-needs'),
            ]),
        ) !!}

        <hr class="divider--thick" />

        <p>
            <a class="cta secondary"
                href="{{ localized_route('engagements.show', $engagement) }}">{{ __('Go to engagement page') }}</a>
        </p>
    </div>
</x-app-wide-layout>
