<x-app-wide-tabbed-layout>
    <x-slot name="title">{{ __('Manage engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
            </li>
        </ol>
        <p class="mt-8 text-2xl"><strong>{{ __('Engagement') }}</strong></p>
        <h1 class="mt-0">
            {{ $engagement->name }}
        </h1>
        @if ($engagement->format)
            <p class="h4">{{ $engagement->display_format }}</p>
        @endif
        <div class="flex flex-col gap-6 md:flex-row md:justify-between">
            <div class="flex flex-col gap-6 md:flex-row md:gap-20">
                <div>
                    <p><strong>{{ __('project.singular_name_titlecase') }}</strong></p>
                    <p>{{ $project->name }}</p>
                </div>
                <div>
                    <p><strong>{{ __('Run by') }}</strong></p>
                    <p>{{ $project->projectable->name }}</p>
                </div>
            </div>
            {{-- TODO: cancel engagement --}}
            {{-- <div> --}}
            {{-- <button class="borderless destructive">{{ __('Cancel engagement') }}</button> --}}
            {{-- </div> --}}
        </div>
    </x-slot>

    <x-manage-grid>
        <x-manage-columns class="col-start-1 col-end-2">
            @if ($engagement->who === 'individuals')
                <x-manage-section :title="__('Recruitment method')">
                    <p class="with-icon">
                        @switch($engagement->recruitment)
                            @case('connector')
                                <x-heroicon-o-user-group class="icon mr-2 h-5 w-5" />
                            @break

                            @case('open-call')
                                <x-heroicon-o-megaphone class="icon mr-2 h-5 w-5" />
                            @break

                            @default
                                <x-heroicon-o-puzzle-piece class="icon mr-2 h-5 w-5" />
                        @endswitch
                        {{ $engagement->display_recruitment }}
                    </p>
                </x-manage-section>
            @endif
            <x-manage-section
                title="{{ $engagement->who === 'individuals' ? __('Participant selection criteria') : __('Organization selection criteria') }}">
                <div>
                    <p class="font-bold">{{ __('Location') }}</p>
                    {!! Str::markdown($engagement->matchingStrategy->location_summary) !!}
                </div>
                <div>
                    <p class="font-bold">{{ __('Disability or Deaf group') }}</p>
                    {!! Str::markdown($engagement->matchingStrategy->disability_and_deaf_group_summary) !!}
                </div>
                <div>
                    <p class="font-bold">{{ __('Other identities') }}</p>
                    {!! Str::markdown($engagement->matchingStrategy->other_identities_summary) !!}
                </div>
                <a class="cta secondary" href="{{ localized_route('engagements.edit-criteria', $engagement) }}">
                    <x-heroicon-o-pencil /> {{ __('Edit') }}
                </a>
            </x-manage-section>
        </x-manage-columns>
        <x-manage-columns class="col-start-2 col-end-4">
            @if ($engagement->checkStatus('draft'))
                <x-manage-section
                    title="{{ !$engagement->hasProvidedRequiredInformation() ? __('Edit engagement details') : __('Review and publish engagement details') }}">
                    @if (!$engagement->hasEstimateAndAgreement())
                        <p>
                            @if (!$engagement->hasProvidedRequiredInformation())
                                {{ __('Please complete your engagement details so potential participants can know what they are signing up for.') }}
                            @else
                                {!! Str::inlineMarkdown(
                                    __(
                                        'You have completed your engagement details, **but you won’t be able to publish them until you [get an estimate](:get_estimate) for this project and approve it**.',
                                        [
                                            'get_estimate' => localized_route('projects.manage', $project),
                                        ],
                                    ),
                                ) !!}
                            @endif
                        </p>
                        <p>
                            <a class="with-icon"
                                href="{{ localized_route('engagements.edit', $engagement) }}">{{ !$engagement->isPublishable() ? __('Edit engagement details') : __('Review engagement details') }}
                                <x-heroicon-m-chevron-right class="icon h-6 w-6" />
                            </a>
                        </p>
                        <p>
                            <span class="badge badge--status badge--stop">
                                <x-heroicon-s-x-circle class="icon mr-2 h-5 w-5" /> {{ __('Not ready to publish') }}
                            </span>
                        </p>
                    @elseif($engagement->hasEstimateAndAgreement())
                        <p>{{ __('Please review and publish your engagement details.') }}</p>
                        <p>
                            <a class="with-icon"
                                href="{{ localized_route('engagements.edit', $engagement) }}">{{ !$engagement->isPublishable() ? __('Edit engagement details') : __('Review and publish engagement details') }}
                                <x-heroicon-m-chevron-right class="icon h-6 w-6" />
                            </a>
                        </p>
                        <p>
                            <span class="badge badge--status badge--go">
                                <x-heroicon-s-check-circle class="icon mr-2 h-5 w-5" /> {{ __('Ready to publish') }}
                            </span>
                        </p>
                    @endif
                </x-manage-section>
            @endif
            @if ($engagement->checkStatus('published'))
                <x-manage-section :title="__('Edit engagement details')" x-data="copyLink">
                    <p>{{ __('Published on :date', ['date' => $engagement->published_at->translatedFormat('F j, Y')]) }}
                    </p>
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-16">
                        <a class="cta secondary" href="{{ localized_route('engagements.edit', $engagement) }}">
                            <x-heroicon-o-pencil /> {{ __('Edit') }} <span
                                class="sr-only">{{ $engagement->name }}</span>
                        </a>
                        <a href="{{ localized_route('engagements.show', $engagement) }}">{{ __('View') }}
                            <span class="sr-only">{{ $engagement->name }}</span></a>
                        <div>
                            <button class="borderless" @click="copy">
                                <x-heroicon-o-clipboard role="presentation" aria-hidden="true" />
                                {{ __('Copy link to share') }}
                            </button>
                        </div>
                        <div class="md:-ml-12" role="alert" aria-live="polite" x-show="alert"
                            x-transition:enter.duration.0ms x-transition:leave.duration.500ms>
                            <p class="font-semibold" x-text="message"></p>
                        </div>
                    </div>
                </x-manage-section>
            @endif

            @if (in_array($engagement->format, ['workshop', 'focus-group', 'other-sync']))
                <x-manage-section :title="__('Engagement meetings')">
                    @forelse($engagement->meetings as $meeting)
                        <article class="flex flex-col gap-4 md:flex-row md:justify-between">
                            <div>
                                <h4>{{ $meeting->title }}</h4>
                                <x-timespan :start="$meeting->start" :end="$meeting->end" />
                                <p>{{ implode(', ', $meeting->display_meeting_types) }}</p>
                            </div>

                            <div class="stack w-full md:w-1/3">
                                <a class="cta secondary with-icon"
                                    href="{{ localized_route('meetings.edit', ['engagement' => $engagement, 'meeting' => $meeting]) }}">
                                    <x-heroicon-o-pencil role="presentation" aria-hidden="true" /> {{ __('Edit') }}
                                </a>
                                <form
                                    action="{{ localized_route('meetings.destroy', ['engagement' => $engagement, 'meeting' => $meeting]) }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="cta secondary destructive with-icon">
                                        <x-heroicon-s-trash role="presentation" aria-hidden="true" />
                                        {{ __('Remove') }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p>{{ __('No meetings found.') }}</p>
                    @endforelse
                    <p>
                        <a class="cta secondary with-icon"
                            href="{{ localized_route('meetings.create', $engagement) }}">
                            <x-heroicon-o-plus-circle />
                            {{ __('Add new meeting') }}
                        </a>
                    </p>
                </x-manage-section>
            @endif

            @if ($engagement->who === 'individuals')
                <x-manage-section :title="__('Estimates and agreements')">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-16">
                        <div class="space-y-2">
                            <p class="font-bold">{{ __('Estimate status') }}</p>
                            <p>
                                @if ($project->checkStatus('estimateApproved'))
                                    <span class="badge badge--status badge--go">
                                        <x-heroicon-s-check-circle class="icon mr-2 h-5 w-5" />
                                        {{ __('Estimate approved') }}
                                    </span>
                                @elseif ($project->checkStatus('estimateRequested'))
                                    <span class="badge badge--status badge--progress">
                                        <x-heroicon-o-arrow-path class="icon mr-2 h-5 w-5" />
                                        {{ __('Estimate requested') }}
                                    </span>
                                @else
                                    <span class="badge badge--status">
                                        {{ __('No estimate requested') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="space-y-2">

                            <p class="font-bold">{{ __('Agreement status') }}</p>
                            <p>
                                @if ($project->checkStatus('agreementReceived'))
                                    <span class="badge badge--status badge--go">
                                        <x-heroicon-s-check-circle class="icon mr-2 h-5 w-5" /> {{ __('Received') }}
                                    </span>
                                @else
                                    <span class="badge badge--status">
                                        {{ __('Not received') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </x-manage-section>
            @endif

            @if ($engagement->who === 'organization')
                <x-manage-section :title="__('Community organization')">
                    @if ($engagement->organization)
                        <x-card.engagement :model="$engagement->organization" />
                    @else
                        <div class="box stack bg-grey-2">
                            <p>{{ __('You currently do not have a Community Organization for this engagement.') }}</p>
                            <p>
                                <a class="cta secondary"
                                    href="{{ localized_route('engagements.manage-organization', $engagement) }}">
                                    <x-heroicon-o-wrench /> {{ __('Manage') }}
                                </a>
                            </p>
                        </div>
                    @endif
                </x-manage-section>
            @endif

            @if ($engagement->recruitment === 'connector')
                <x-manage-section :title="__('Community Connector')">
                    <p>{{ __('Find a community connector to help you recruit participants.') }}</p>
                    @if ($engagement->connector)
                        <x-card.individual :model="$engagement->connector" />
                    @elseif($engagement->organizationalConnector)
                        <x-card.organization :model="$engagement->organizationalConnector" />
                    @elseif($connectorInvitation && $connectorInvitation->where('role', 'connector'))
                        @if ($connectorInvitation->type === 'individual')
                            @if ($connectorInvitee)
                                <x-card.individual level="3" :model="$connectorInvitee" />
                            @else
                                <p>{{ $connectorInvitation->email }} <span class="badge">{{ __('Pending') }}</span>
                                </p>
                            @endif
                        @elseif($connectorInvitation->type === 'organization')
                            <x-card.organization level="3" :model="$connectorInvitee" />
                        @endif
                    @endif
                    @if ($engagement->hasEstimateAndAgreement())
                        <p>
                            <a class="cta secondary"
                                href="{{ localized_route('engagements.manage-connector', $engagement) }}">
                                <x-heroicon-o-wrench /> {{ __('Manage') }}
                            </a>
                        </p>
                    @else
                        <x-hearth-alert>
                            {{ __('This can only be done after you have added your engagement details and approved your estimate.') }}
                        </x-hearth-alert>
                    @endif
                </x-manage-section>
            @endif
            @if ($engagement->who === 'individuals')
                <x-manage-section :title="__('Manage participants')">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-16">
                        <p>{!! __(':count participants confirmed', [
                            'count' => '<span class="h4">' . $engagement->confirmedParticipants->count() . '</span><br />',
                        ]) !!}</p>
                        {{-- TODO: aggregate participant access needs --}}
                        {{-- <p>{{ __(':count access needs listed', ['count' => $engagement->confirmedParticipantAccessNeeds->count()]) }}</p> --}}
                    </div>
                    <p>
                        {{-- TODO: manage participants --}}
                        <a class="cta secondary"
                            href="{{ localized_route('engagements.manage-participants', $engagement) }}">
                            <x-heroicon-o-users /> {{ __('Manage participants') }}
                        </a>
                    </p>
                </x-manage-section>
            @endif
        </x-manage-columns>
    </x-manage-grid>
    <script>
        function copyLink() {
            return {
                link: '{{ localized_route('engagements.show', $engagement) }}',
                message: "",
                status: false,
                alert: false,
                copy() {
                    navigator.clipboard.writeText(this.link).then(() => {
                        this.message = '{{ __('Link copied!') }}';
                        this.status = "success";
                        this.alert = true;
                        setTimeout(() => this.alert = false, 3000);
                    }, () => {
                        this.message = '{{ __('The link could not be copied.') }}';
                        this.status = "failure";
                        this.alert = true;
                        setTimeout(() => this.alert = false, 3000);
                    });
                }
            };
        }
    </script>
</x-app-wide-tabbed-layout>
