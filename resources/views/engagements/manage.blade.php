<x-app-wide-tabbed-layout>
    <x-slot name="title">{{ __('Manage engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="{{ localized_route('projects.show', $engagement->project) }}">{{ $engagement->project->name }}</a>
            </li>
        </ol>
        <p class="mt-8 text-2xl"><strong>{{ __('Engagement') }}</strong></p>
        <h1 class="mt-0">
            {{ $engagement->name }}
        </h1>
        <p class="h4">{{ $engagement->display_format }}</p>
        <div class="flex flex-col gap-6 md:flex-row md:justify-between">
            <div class="flex flex-col gap-6 md:flex-row md:gap-20">
                <div>
                    <p><strong>{{ __('project.singular_name_titlecase') }}</strong></p>
                    <p>{{ $engagement->project->name }}</p>
                </div>
                <div>
                    <p><strong>{{ __('Run by') }}</strong></p>
                    <p>{{ $engagement->project->projectable->name }}</p>
                </div>
            </div>
            <div>
                {{-- TODO: cancel engagement --}}
                <button class="borderless destructive">{{ __('Cancel engagement') }}</button>
            </div>
        </div>
    </x-slot>

    <div class="w-prose mt-12 flex grid-cols-3 flex-col gap-8 lg:grid">
        <div class="col-start-1 col-end-2 flex flex-col gap-8">
            <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md">
                <h3>{{ __('Recruitment method') }}</h3>
                <p>{{ $engagement->display_recruitment }}</p>
            </div>
            <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md">
                <h3>{{ __('Participant selection criteria') }}</h3>
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
                <a class="cta secondary"
                    href="{{ localized_route('engagements.edit-criteria', $engagement) }}">{{ __('Edit') }}</a>
            </div>
            <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md">
                <h3>{{ __('Accessibility Consultant') }}</h3>
                @if (!$engagement->consultant && !$engagement->organizationalConsultant)
                    <div class="box stack bg-grey-2">
                        <p>{{ __('You do not have an Accessibility Consultant on this engagement.') }}</p>
                        {{-- TODO: add/manage accessibility consultant --}}
                    </div>
                @endif
            </div>
        </div>
        <div class="col-start-2 col-end-4 flex flex-col gap-8">
            <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md" x-data="copyLink">
                <h3>{{ __('Engagement details') }}</h3>
                @if ($engagement->checkStatus('draft'))
                    <p>{{ __('Please complete your engagement details so potential participants can know what they are signing up for.') }}
                    </p>
                    <div class="box stack bg-grey-2">
                        <p>{{ __('You have not finished adding your engagement details.') }}</p>
                        <p>
                            <a class="cta secondary"
                                href="{{ localized_route('engagements.edit', $engagement) }}">{{ __('Edit engagement details') }}</a>
                        </p>
                    </div>
                @elseif($engagement->checkStatus('published'))
                    <p>{{ __('Last updated on :date', ['date' => $engagement->published_at->translatedFormat('F j, Y')]) }}
                    </p>
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-16">
                        <a class="cta secondary"
                            href="{{ localized_route('engagements.edit', $engagement) }}">{{ __('Edit engagement details') }}</a>
                        <a href="{{ localized_route('engagements.show', $engagement) }}">{{ __('View') }} <span
                                class="sr-only">{{ $engagement->name }}</span></a>
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
                @endif
            </div>
            @if ($engagement->recruitment === 'connector')
                <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md">
                    <h3>{{ __('Community Connector') }}</h3>
                    <p>{{ __('Find a community connector to help you recruit participants.') }}</p>
                    <x-hearth-alert>
                        {{ __('This can only be done after you have added your engagement details and approved your estimate.') }}
                    </x-hearth-alert>
                </div>
            @endif
            <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md">
                <h3>{{ __('Manage participants') }}</h3>
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-16">
                    <p>{!! __(':count participants confirmed', [
                        'count' => '<span class="h4">' . $engagement->confirmedParticipants->count() . '</span><br />',
                    ]) !!}</p>
                    {{-- TODO: aggregate participant access needs --}}
                    {{-- <p>{{ __(':count access needs listed', ['count' => $engagement->confirmedParticipantAccessNeeds->count()]) }}</p> --}}
                </div>
                <p>
                    {{-- TODO: manage participants --}}
                    <a class="cta secondary" href="#">{{ __('Manage participants') }}</a>
                </p>
            </div>
            <div class="space-y-6 rounded bg-white px-6 py-8 shadow-md">
                <h3>{{ __('Documents') }}</h3>
                <p>{{ __('This includes reports, contracts, or anything else you would like Consultation Participants to be able to access.') }}
                </p>
            </div>
        </div>
    </div>
    <script>
        function copyLink() {
            return {
                link: '{{ localized_route('engagements.show', $engagement) }}',
                message: '',
                status: false,
                alert: false,
                copy() {
                    navigator.clipboard.writeText(this.link).then(() => {
                        this.message = '{{ __('Link copied!') }}';
                        this.status = 'success';
                        this.alert = true;
                        setTimeout(() => this.alert = false, 3000);
                    }, () => {
                        this.message = '{{ __('The link could not be copied.') }}';
                        this.status = 'failure';
                        this.alert = true;
                        setTimeout(() => this.alert = false, 3000);
                    });
                }
            }
        }
    </script>
</x-app-wide-tabbed-layout>
