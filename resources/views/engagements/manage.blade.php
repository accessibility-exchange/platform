<x-app-layout header-class="header--tabbed" page-width="wide">
    <x-slot name="title">{{ __('Manage engagement') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack">
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
        </div>
    </x-slot>

    <x-manage-grid>
        <x-manage-columns class="col-start-1 col-end-2">
            @if ($engagement->who === 'individuals')
                <x-manage-section :title="__('Recruitment method')">
                    <p class="with-icon">
                        @switch($engagement->recruitment)
                            @case('connector')
                                @svg('heroicon-o-user-group', 'mr-2')
                            @break

                            @case('open-call')
                                @svg('heroicon-o-megaphone', 'mr-2')
                            @break

                            @default
                                @svg('heroicon-o-puzzle-piece', 'mr-2')
                        @endswitch
                        {{ $engagement->display_recruitment }}
                    </p>
                </x-manage-section>
            @endif
            <x-manage-section
                title="{{ $engagement->who === 'individuals' ? __('Participant selection criteria') : __('Organization selection criteria') }}">
                <div>
                    <p class="font-bold">{{ __('Location') }}</p>
                    <x-array-list-view :data="$engagement->matchingStrategy->location_summary" />
                </div>
                <div>
                    <p class="font-bold">{{ __('Disability or Deaf group') }}</p>
                    <x-array-list-view :data="$engagement->matchingStrategy->disability_and_deaf_group_summary" />
                </div>
                <div>
                    <p class="font-bold">{{ __('Other identities') }}</p>
                    <x-array-list-view :data="$engagement->matchingStrategy->other_identities_summary" />
                </div>
                <a class="cta secondary" href="{{ localized_route('engagements.edit-criteria', $engagement) }}">
                    @svg('heroicon-o-pencil') {{ __('Edit') }}
                </a>
            </x-manage-section>
        </x-manage-columns>
        <x-manage-columns class="col-start-2 col-end-4">
            @if ($engagement->checkStatus('draft'))
                <x-manage-section
                    title="{{ !$engagement->isPreviewable() ? __('Edit engagement details') : __('Review and publish engagement details') }}">
                    @if ($engagement->who === 'individuals')
                        @if (!$engagement->hasEstimateAndAgreement())
                            <p>
                                @if (!$engagement->isPreviewable())
                                    {{ __('Please complete your engagement details so potential participants can know what they are signing up for.') }}
                                @else
                                    {{ safe_inlineMarkdown(
                                        'You have completed your engagement details, **but you won’t be able to publish them until you [get an estimate](:get_estimate) for this project and approve it**.',
                                        [
                                            'get_estimate' => localized_route('projects.manage-estimates-and-agreements', $project),
                                        ],
                                    ) }}
                                @endif
                            </p>
                            <p>
                                <a class="with-icon"
                                    href="{{ localized_route('engagements.edit', $engagement) }}">{{ !$engagement->isPublishable() ? __('Edit engagement details') : __('Review engagement details') }}
                                    @svg('heroicon-s-chevron-right', 'icon--lg')
                                </a>
                            </p>
                            <p>
                                <span class="badge badge--stop">
                                    @svg('heroicon-s-x-circle', 'mr-2')
                                    {{ __('Not ready to publish') }}
                                </span>
                            </p>
                        @elseif($engagement->hasEstimateAndAgreement())
                            @if (!$engagement->isPreviewable())
                                {{ __('Please complete your engagement details so potential participants can know what they are signing up for.') }}
                            @else
                                <p>{{ __('Please review and publish your engagement details.') }}</p>
                            @endif
                            <p>
                                <a class="with-icon"
                                    href="{{ localized_route('engagements.edit', $engagement) }}">{{ !$engagement->isPublishable() ? __('Edit engagement details') : __('Review and publish engagement details') }}
                                    @svg('heroicon-s-chevron-right', 'icon--lg')
                                </a>
                            </p>
                            <p>
                                <span @class([
                                    'badge',
                                    'badge--stop' => !$engagement->isPreviewable(),
                                    'badge--go' => $engagement->isPreviewable(),
                                ])>
                                    @if (!$engagement->isPreviewable())
                                        @svg('heroicon-s-x-circle', 'mr-2') {{ __('Not ready to publish') }}
                                    @else
                                        @svg('heroicon-s-check-circle', 'mr-2') {{ __('Ready to publish') }}
                                    @endif
                                </span>
                            </p>
                        @endif
                    @else
                        <p>{{ $engagement->isPreviewable() ? __('Please review and publish your engagement details.') : __('Please complete your engagement details.') }}
                        </p>
                        <p>
                            <a class="with-icon"
                                href="{{ localized_route('engagements.edit', $engagement) }}">{{ !$engagement->isPublishable() ? __('Edit engagement details') : __('Review engagement details') }}
                                @svg('heroicon-s-chevron-right', 'icon--lg')
                            </a>
                        </p>
                        <p>
                            <span @class([
                                'badge',
                                'badge--stop' => !$engagement->isPreviewable(),
                                'badge--go' => $engagement->isPreviewable(),
                            ])>
                                @if (!$engagement->isPreviewable())
                                    @svg('heroicon-s-x-circle', 'mr-2') {{ __('Not ready to publish') }}
                                @else
                                    @svg('heroicon-s-check-circle', 'mr-2') {{ __('Ready to publish') }}
                                @endif
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
                            @svg('heroicon-o-pencil', 'mr-1') {{ __('Edit') }} <span
                                class="sr-only">{{ $engagement->name }}</span>
                        </a>
                        <a href="{{ localized_route('engagements.show', $engagement) }}">{{ __('View') }}
                            <span class="sr-only">{{ $engagement->name }}</span></a>
                        <div>
                            <button class="borderless" @click="copy">
                                @svg('heroicon-o-clipboard')
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
                                    @svg('heroicon-o-pencil', 'mr-1') {{ __('Edit') }}
                                </a>
                                <form
                                    action="{{ localized_route('meetings.destroy', ['engagement' => $engagement, 'meeting' => $meeting]) }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="cta secondary destructive with-icon">
                                        @svg('heroicon-s-trash')
                                        {{ __('Remove') }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p>{{ __('No meetings found.') }}</p>

                        <x-hearth-alert x-show="true" :dismissable="false">
                            {{ __('You won’t be able to publish your engagement until you’ve added meetings.') }}
                        </x-hearth-alert>
                    @endforelse
                    <p>
                        <a class="cta secondary with-icon"
                            href="{{ localized_route('meetings.create', $engagement) }}">
                            @svg('heroicon-o-plus-circle')
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
                                    <span class="badge badge--go">
                                        @svg('heroicon-s-check-circle', 'mr-2')
                                        {{ __('Estimate approved') }}
                                    </span>
                                @elseif ($project->checkStatus('estimateRequested'))
                                    <span class="badge badge--progress">
                                        @svg('heroicon-o-arrow-path', 'mr-2')
                                        {{ __('Estimate requested') }}
                                    </span>
                                @else
                                    <span class="badge">
                                        {{ __('No estimate requested') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="space-y-2">

                            <p class="font-bold">{{ __('Agreement status') }}</p>
                            <p>
                                @if ($project->checkStatus('agreementReceived'))
                                    <span class="badge badge--go">
                                        @svg('heroicon-s-check-circle', 'mr-2') {{ __('Received') }}
                                    </span>
                                @else
                                    <span class="badge">
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
                        <x-card.organization level="4" :model="$engagement->organization" />
                        <footer class="-mx-6 border-x-0 border-b-0 border-t border-solid border-grey-3 px-6 pt-5">
                            <a class="cta secondary"
                                href="{{ localized_route('engagements.manage-organization', $engagement) }}">
                                @svg('heroicon-o-cog') {{ __('Manage') }}
                            </a>
                        </footer>
                    @else
                        <div class="box stack">
                            <p>{{ __('You currently do not have a Community Organization for this engagement.') }}</p>
                            <p>
                                <a class="cta secondary"
                                    href="{{ localized_route('engagements.manage-organization', $engagement) }}">
                                    @svg('heroicon-o-cog') {{ __('Manage') }}
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
                                <x-card.individual level="4" :model="$connectorInvitee" />
                            @else
                                <p>{{ $connectorInvitation->email }} <span class="badge">{{ __('Pending') }}</span>
                                </p>
                            @endif
                        @elseif($connectorInvitation->type === 'organization')
                            <x-card.organization level="4" :model="$connectorInvitee" />
                        @endif
                    @endif
                    @if ($engagement->hasEstimateAndAgreement())
                        <p>
                            <a class="cta secondary"
                                href="{{ localized_route('engagements.manage-connector', $engagement) }}">
                                @svg('heroicon-o-cog') {{ __('Manage') }}
                            </a>
                        </p>
                    @else
                        <x-hearth-alert x-show="true" :dismissable="false">
                            {{ __('This can only be done after you have added your engagement details and approved your estimate.') }}
                        </x-hearth-alert>
                    @endif
                </x-manage-section>
            @endif
            @if ($engagement->who === 'individuals')
                <x-manage-section :title="__('Manage participants')">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-16">
                        <p>
                            <span class="h4">{{ $engagement->confirmedParticipants->count() }}</span>
                            <br>
                            {{ __('participants confirmed') }}
                        </p>
                        {{-- TODO: aggregate participant access needs --}}
                        {{-- <p>{{ __(':count access needs listed', ['count' => $engagement->confirmedParticipantAccessNeeds->count()]) }}</p> --}}
                    </div>
                    <p>
                        {{-- TODO: manage participants --}}
                        <a class="cta secondary"
                            href="{{ localized_route('engagements.manage-participants', $engagement) }}">
                            @svg('heroicon-o-users') {{ __('Manage participants') }}
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
</x-app-layout>
