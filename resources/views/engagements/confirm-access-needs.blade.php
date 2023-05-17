<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Confirm your access needs') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1 class="w-full md:w-2/3">
            {{ __('You have successfully signed up') }}
        </h1>
    </x-slot>

    <div class="stack mb-12 w-full md:w-2/3">
        <h2>{{ __('Confirm your access needs') }}</h2>

        <p>{{ __('You have successfully signed up, and your name and your contact information have been shared with :projectable. Please confirm your access needs so they can be shared with :projectable (without your name beside it):', ['projectable' => $project->projectable->name]) }}
        </p>

        <ul class="my-8 space-y-6" role="list">
            @forelse(Auth::user()->individual->accessSupports as $support)
                <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">{{ $support->name }}</li>
            @empty
                @if (blank(Auth::user()->individual->other_access_need))
                    <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">
                        {{ __('No access needs found.') }}
                    </li>
                @endif
            @endforelse
            @unless(blank(Auth::user()->individual->other_access_need))
                <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">
                    {{ Auth::user()->individual->other_access_need }}
                </li>
            @endunless
        </ul>

        <div class="grid">
            <div class="flex flex-col">
                @if ($hasIdentifiableAccessNeeds)
                    <a class="cta secondary"
                        href="{{ localized_route('engagements.edit-access-needs-permissions', $engagement) }}">@svg('heroicon-s-check')
                        {{ __('Confirm') }}</a>
                @else
                    <form class="flex flex-col"
                        action="{{ localized_route('engagements.store-access-needs-permissions', $engagement) }}"
                        method="post">
                        @csrf
                        <button class="secondary" name="share_access_needs" value="0">
                            @svg('heroicon-s-check') {{ __('Confirm') }}
                        </button>
                    </form>
                @endif
            </div>
            <div class="flex flex-col">
                <a class="cta secondary"
                    href="{{ localized_route('settings.edit-access-needs', ['engagement' => $engagement]) }}">@svg('heroicon-s-pencil')
                    {{ __('Edit') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
