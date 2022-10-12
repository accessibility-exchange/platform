<x-app-wide-tabbed-layout>
    <x-slot name="title">
        @section('title'){{ __('Manage participants') }}@show
        </x-slot>
        <x-slot name="header">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
                <li><a
                        href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
                </li>
                <li><a
                        href="@can('update', $engagement){{ localized_route('engagements.manage', $engagement) }}@else{{ localized_route('engagements.show', $engagement) }}@endcan">{{ $engagement->name }}</a>
                </li>
                @yield('breadcrumbs')
            </ol>
            <h1 id="project">
                {{ __('Manage participants') }}
            </h1>
        </x-slot>

    @section('navigation')
        <nav class="full mb-12 bg-white shadow-md"
            aria-labelledby="{{ __(':name participants navigation', ['name' => $engagement->name]) }}">
            <div class="center center:wide">
                <ul class="-mt-4 flex gap-6" role="list">
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('engagements.manage-participants', $engagement)"
                            :active="request()->localizedRouteIs('engagements.manage-participants', $engagement)">
                            {{ __('Participants') }}
                        </x-nav-link>
                    </li>
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('engagements.manage-access-needs', $engagement)"
                            :active="request()->localizedRouteIs('engagements.manage-access-needs', $engagement)">
                            {{ __('Access Needs') }}
                        </x-nav-link>
                    </li>
                </ul>
            </div>
        </nav>
    @show

    @section('content')
        <div class="repel">
            <h2>{{ __('Participants') }}</h2>
            @can('manageParticipants', $engagement)
                @if ($participants->count() < $engagement->ideal_participants)
                    <a class="cta secondary with-icon"
                        href="{{ localized_route('engagements.add-participant', $engagement) }}">
                        <x-heroicon-o-plus-circle role="presentation" aria-hidden="true" />
                        {{ __('Add participant') }}
                    </a>
                @endif
            @endcan
        </div>

        <hr class="divider--thick" />

        @if ($invitations->count())
            <h3 id="pending">{{ __('Pending') }}</h3>
            <div role="region" aria-labelledby="pending" tabindex="0">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                        </tr>
                    </thead>
                    @foreach ($invitations as $invitation)
                        <tr>
                            <td>{{ __('Not available.') }}</td>
                            <td>{{ $invitation->email }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <hr class="divider--thick" />
        @endif

        <h3 id="confirmed">{{ __('Confirmed participants') }}</h3>
        @if ($participants->count())
            <div role="region" aria-labelledby="pending" tabindex="0">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    @foreach ($participants as $participant)
                        <tr>
                            <td>{{ $participant->name }}</td>
                            <td>{{ $participant->email }}</td>
                            <td>{{ $participant->phone }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            <p>{{ __('No confirmed participants found.') }}</p>
        @endif
    @show
</x-app-wide-tabbed-layout>
