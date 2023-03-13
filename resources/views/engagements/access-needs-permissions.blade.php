<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Confirm your access needs') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
            <li><a
                    href="{{ localized_route('engagements.confirm-access-needs', $engagement) }}">{{ __('Confirm your access needs') }}</a>
            </li>
        </ol>
        <h1 class="w-full md:w-2/3">
            {{ __('Sharing your access needs') }}
        </h1>
    </x-slot>

    <div class="stack mb-12 w-full md:w-2/3">
        <p>{{ __('Some of the access needs you’ve chosen need :projectable to directly contact you to arrange and deliver. Are you okay with us putting your name beside this access need?', ['projectable' => $project->projectable->name]) }}
        </p>

        {!! Str::markdown(
            __(
                '**If you select no,** our support line will contact you and arrange for a different way to have your access needs met.',
            ),
        ) !!}

        <ul class="my-8 space-y-6" role="list">
            @forelse($identifiableAccessSupports as $support)
                <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">{{ $support->name }}
                </li>
            @empty
                <li class="border border-x-0 border-b-0 border-solid border-t-graphite-6 pt-5">
                    {{ __('No access needs found.') }}
                </li>
            @endforelse
        </ul>

        <form class="mt-12 flex flex-row gap-6"
            action="{{ localized_route('engagements.store-access-needs-permissions', $engagement) }}" method="post">
            @csrf
            <div class="grid">
                <div>
                    <button class="secondary" name="share_access_needs" value="1">
                        @svg('heroicon-s-check') {{ __('Yes, share my access needs') }}
                    </button>
                </div>
                <div class="flex flex-col">
                    <button class="secondary" name="share_access_needs" value="0"
                        aria-describedby="share_access_needs-no">
                        @svg('heroicon-s-x') {{ __('No, don’t share my access needs') }}
                    </button>
                    <p class="mt-2" id="share_access_needs-no">
                        {{ __('If you select no, our support line will contact you and arrange for a way to have your access needs met.') }}
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
