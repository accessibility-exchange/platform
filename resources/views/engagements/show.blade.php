<x-app-wide-layout>
    <x-slot name="title">{{ $engagement->name }}</x-slot>
    <x-slot name="header">
        @if (auth()->hasUser() &&
            auth()->user()->isAdministrator() &&
            $engagement->project->projectable->checkStatus('suspended'))
            @push('banners')
                <div class="banner banner--error">
                    <div class="center center:wide">
                        <p>
                            @svg('heroicon-s-ban', 'icon--lg mr-2')
                            <span>{{ __('This account has been suspended.') }}</span>
                        </p>
                    </div>
                </div>
            @endpush
        @endif
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li>
                <a
                    href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
            </li>
        </ol>
        <h1 id="engagement">
            {{ $engagement->name }}
        </h1>
        @if ($engagement->format)
            <p class="h4">{{ $engagement->display_format }}</p>
        @elseif($engagement->who === 'organization')
            <p class="h4">{{ __('Consulting with a Community Organization') }}</p>
        @endif

        <div class="flex flex-col gap-6 md:flex-row md:items-start md:gap-16">
            <dl class="flex flex-col gap-6 md:flex-row md:items-start md:gap-16">
                <div>
                    <dt>{{ __('project.singular_name_titlecase') }}</dt>
                    <dd><a
                            href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
                    </dd>
                </div>
                <div>
                    <dt>{{ __('Run by') }}</dt>
                    <dd><a
                            href="{{ localized_route($project->projectable->getRoutePrefix() . '.show', $project->projectable) }}">{{ $project->projectable->name }}</a>
                    </dd>
                </div>
                @if ($engagement->recruitment)
                    <div>
                        <dt>{{ __('Recruitment') }}</dt>
                        <dd>
                            {{ $engagement->display_recruitment }}
                            @if (($engagement->recruitment === 'connector' && $engagement->connector) || $engagement->organizationalConnector)
                                <br />
                                @if ($engagement->connector)
                                    <a
                                        href="{{ localized_route('individuals.show', $engagement->connector) }}">{{ $engagement->connector->name }}</a>
                                @elseif($engagement->organizationalConnector)
                                    <a
                                        href="{{ localized_route('organizations.show', $engagement->organizationalConnector) }}">{{ $engagement->organizationalConnector->name }}</a>
                                @endif
                            @endif
                        </dd>
                    </div>
                @endif
            </dl>

            @can('join', $engagement)
                <a class="cta" href="{{ localized_route('engagements.sign-up', $engagement) }}">
                    @svg('heroicon-o-clipboard-check') {{ __('Sign up') }}
                </a>
            @endcan

            @can('participate', $engagement)
                <a class="cta secondary" href="{{ localized_route('engagements.confirm-leave', $engagement) }}">
                    @svg('heroicon-o-logout')
                    {{ __('Leave engagement') }}
                </a>
            @endcan
        </div>

        @can('update', $engagement)
            <a class="cta secondary"
                href="{{ localized_route('engagements.manage', $engagement) }}">{{ __('Manage engagement') }}</a>
        @endcan

        @can('manageParticipants', $engagement)
            <a class="cta secondary" href="{{ localized_route('engagements.manage-participants', $engagement) }}">
                @svg('heroicon-o-users') {{ __('Manage participants') }}
            </a>
        @endcan
    </x-slot>

    <x-language-changer :model="$project" />

    <div class="stack mb-12 w-full md:w-2/3">
        <h2>{{ __('Description') }}</h2>

        {!! Str::markdown($engagement->description) !!}

        <hr class="divider--thick" />

        <h2>{{ __('Who we’re looking for') }}</h2>

        <h3>{{ __('Location') }}</h3>

        {!! Str::markdown($engagement->matchingStrategy->location_summary) !!}

        <h3>{{ __('Disability or Deaf group') }}</h3>

        {!! Str::markdown($engagement->matchingStrategy->disability_and_deaf_group_summary) !!}

        <h3>{{ __('Other identities') }}</h3>

        {!! Str::markdown($engagement->matchingStrategy->other_identities_summary) !!}

        <hr class="divider--thick" />

        @if (in_array($engagement->format, ['workshop', 'focus-group', 'other-sync']))
            <h2>{{ __('Meetings') }}</h2>
            <div class="space-y-6">
                @forelse($engagement->meetings as $meeting)
                    <article class="box stack">
                        <h3>{{ $meeting->title }}</h3>
                        <h4>{{ __('Date') }}</h4>
                        <x-timespan :start="$meeting->start" :end="$meeting->end" />
                        <h4>{{ __('Ways to attend') }}</h4>
                        @foreach ($meeting->meeting_types as $type)
                            <h5>{{ App\Enums\MeetingType::labels()[$type] }}</h5>
                            @include('engagements.partials.details-' . $type, ['meeting' => $meeting])
                        @endforeach
                    </article>
                @empty
                    <p>{{ __('No meetings found.') }}</p>
                @endforelse
            </div>
        @endif

        @if ($engagement->format === 'interviews')
            <h2>{{ __('Date range') }}</h2>
            <p>{{ __('Interviews will take place between :start and :end.', ['start' => $engagement->window_start_date->isoFormat('LL'), 'end' => $engagement->window_end_date->isoFormat('LL')]) }}
            </p>
            <hr class="divider--thick" />
            <h2>{{ __('Ways to participate') }}</h2>
            <h3>{{ __('Real time interview') }}</h3>
            <p>{{ __('Attend an interview in real time.') }}</p>
            <h4>{{ __('Days of the week interviews will be happening') }}</h4>
            <ul class="space-y-4" role="list">
                @foreach (\App\Enums\Weekday::labels() as $key => $day)
                    <li class="flex items-center">
                        @switch($engagement->weekday_availabilities[$key])
                            @case('no')
                                @svg('heroicon-s-x-circle', 'mr-2 icon--red')
                                <span><span class="font-semibold">{{ $day }}</span> —
                                    {{ __('not available') }}</span>
                            @break

                            @case('upon-request')
                                @svg('heroicon-s-question-mark-circle', 'mr-2 icon--yellow') <span><span class="font-semibold">{{ $day }}</span> —
                                    {{ __('upon request') }}</span>
                            @break

                            @default
                                @svg('heroicon-s-check-circle', 'mr-2 icon--green')
                                <span><span class="font-semibold">{{ $day }}</span> — {{ __('available') }}</span>
                        @endswitch
                    </li>
                @endforeach
            </ul>
            <h4>{{ __('Times during the day interviews will be happening') }}</h4>
            <h5>{{ __('Start time') }}</h5>
            <p>{{ $engagement->window_start_time->isoFormat('LT') }}</p>
            <h5>{{ __('End time') }}</h5>
            <p>{{ $engagement->window_end_time->isoFormat('LT') }}</p>
            <h5>{{ __('Time zone') }}</h5>
            <p>{{ Illuminate\Support\Carbon::now($engagement->timezone)->isoFormat('z') }}</p>
            <h4>{{ __('Ways to attend') }}</h4>
            @foreach ($engagement->meeting_types as $type)
                <h5>{{ App\Enums\MeetingType::labels()[$type] }}</h5>
                @include('engagements.partials.details-' . $type, ['meeting' => $engagement])
            @endforeach
            <h3>{{ __('Interview at your own pace') }}</h3>
            <p>{{ __('The :projectable sends out a list of questions, and you can can respond to them at your own pace.', ['projectable' => $project->projectable->getSingularName()]) }}
            </p>
            <h4>{{ __('Dates') }}</h4>
            <h5>{{ __('Questions are sent to participants by:') }}</h5>
            <p>{{ $engagement->materials_by_date->isoFormat('LL') }}</p>
            <h5>{{ __('Responses are due by:') }}</h5>
            <p>{{ $engagement->complete_by_date->isoFormat('LL') }}</p>
            <h4>{{ __('Accepted formats') }}</h4>
            <ul class="divide-y-graphite-6 divide-y divide-x-0 divide-solid" role="list">
                @foreach ($engagement->accepted_formats as $format)
                    <li class="py-4">{{ \App\Enums\AcceptedFormat::labels()[$format] }}</li>
                @endforeach
                @if ($engagement->other_accepted_format)
                    <li class="py-4">{{ $engagement->other_accepted_format }}</li>
                @endif
            </ul>
        @endif

        @if (in_array($engagement->format, ['survey', 'other-async']))
            <h2>{{ $engagement->format === 'survey' ? __('Survey materials') : __('Engagement materials') }}</h2>
            <h3>{{ __('Dates') }}</h3>
            <h4>{{ __('Documents will be sent to participants by:') }}</h4>
            <p>{{ $engagement->materials_by_date->isoFormat('LL') }}</p>
            <h4>{{ __('Completed documents are due by:') }}</h4>
            <p>{{ $engagement->complete_by_date->isoFormat('LL') }}</p>
            <h3>{{ __('Languages') }}</h3>
            <p>{{ __('Materials will be provided in the following languages:') }}</p>
            <ul class="divide-y-graphite-6 divide-y divide-x-0 divide-solid" role="list">
                @foreach ($engagement->document_languages as $code)
                    <li class="py-4">{{ get_language_exonym($code) }}</li>
                @endforeach
            </ul>
        @endif

        @if ($engagement->who === 'organization')
            <h2>{{ __('Community Organization') }}</h2>
            <p>{{ __('The Community Organization being consulted with for this engagement.') }}</p>
            @if ($engagement->organization)
                <div class="mt-10 mb-12">
                    <x-card.organization :model="$engagement->organization" level="3" />
                </div>
            @endif
        @else
            <hr class="divider--thick" />

            <h2>{{ __('Payment') }}</h2>

            <p class="mb-12">
                @if ($engagement->paid)
                    {!! Str::inlinemarkdown(__('This engagement is a **paid** opportunity.')) !!}
                @else
                    {!! Str::inlineMarkdown(__('This engagement is a **volunteer** opportunity.')) !!}
                @endif
            </p>
        @endif

        <x-hearth-alert :title="__('Have questions?')" :dismissable="false" x-show="true">
            <p>
                <strong>{{ __('Do you have questions about how the engagement works?') }}</strong><br />
                {{ __('Contact :contact_person_name from :projectable at:', ['contact_person_name' => $project->contact_person_name, 'projectable' => $project->projectable->name]) }}
            </p>
            <div class="flex flex-col">
                @if ($project->contact_person_email)
                    <div class="with-icon">
                        @svg('heroicon-o-mail')
                        <span><strong>{{ __('Email:') }}</strong> <a
                                href="mailto:{{ $project->contact_person_email }}">{{ $project->contact_person_email }}</a></span>
                    </div>
                @endif
                @if ($project->contact_person_phone)
                    <div class="with-icon">
                        @svg('heroicon-o-phone')
                        <span><strong>{{ __('Phone:') }}</strong>
                            {{ $project->contact_person_phone->formatForCountry('CA') }}</span>
                    </div>
                @endif
            </div>
        </x-hearth-alert>
    </div>
</x-app-wide-layout>
