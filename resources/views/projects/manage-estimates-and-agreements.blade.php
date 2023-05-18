@extends('projects.manage')

@section('title')
    {{ __('Estimates and agreements') }}
@endsection

@section('breadcrumbs')
    <li><a href="{{ localized_route('projects.manage', $project) }}">{{ $project->name }}</a></li>
@endsection

@section('content')
    <h2>{{ __('Estimates and agreements') }}</h2>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="stack w-full md:w-1/2">
            <h3>{{ __('Estimates') }}</h3>
            <p><strong>{{ __('Status') }}</strong></p>
            @if ($project->estimate_requested_at)
                @if ($project->estimate_approved_at)
                    <p><span class="badge">{{ __('Approved') }}</span></p>
                @elseif($project->estimate_returned_at)
                    <p><span class="badge">{{ __('Returned') }}</span></p>
                    <p>{{ __('This estimate was sent to :contact on :date.', ['contact' => $project->contact_person_email, 'date' => $project->estimate_requested_at->translatedFormat('F j, Y')]) }}
                        @include('projects.partials.included-engagements')
                    <div class="flex items-center gap-6">
                        <livewire:estimate-approver :model="$project" />
                        <a href="mailto:{{ settings('email') }}">{{ __('Contact us') }}
                            @svg('heroicon-s-chevron-right')
                        </a>
                    </div>
                @else
                    <p><span class="badge">{{ __('Pending') }}</span></p>
                    <p>{{ __('You sent this request on :date.', ['date' => $project->estimate_requested_at->translatedFormat('F j, Y')]) }}
                    </p>
                    @include('projects.partials.included-engagements')
                @endif
            @else
                <p>
                    <span class="badge">
                        {{ __('Not yet requested') }}
                    </span>
                </p>
                <h4>{{ __('New estimate request') }}</h4>
                @if ($project->isPublishable() && $engagements->count())
                    @include('projects.partials.included-engagements')
                    <x-hearth-alert x-show="true" :dismissable="false" :title="__('Missing an engagement?')">
                        <p>{{ __('To include an engagement in a quote request, you must have filled out the engagement details (and meeting information for workshops and focus groups).') }}
                        </p>
                    </x-hearth-alert>
                    <livewire:estimate-requester :model="$project" />
                @else
                    @if (!$project->isPublishable())
                        <x-hearth-alert type="warning" x-show="true" :dismissable="false" :title="__('Project page incomplete')">
                            <p>{{ __('To request an estimate, you must have created your project’s page.') }}
                            </p>
                        </x-hearth-alert>
                    @endif
                    @if (!$engagements->count())
                        <x-hearth-alert type="warning" x-show="true" :dismissable="false" :title="__('No engagements found')">
                            <p>{{ __('To request an estimate, you must have filled out your project’s engagement details (and meeting information for workshops and focus groups).') }}
                            </p>
                        </x-hearth-alert>
                    @endif
                @endif
            @endif
        </div>
        <div class="stack w-full md:w-1/2">
            <h3>{{ __('Agreements') }}</h3>
            <p>{!! __('The agreement will be sent with your estimate. Please sign this agreement and send it to :email.', [
                'email' => Str::inlineMarkdown('<' . settings('email') . '>'),
            ]) !!}</p>
            <p><strong>{{ __('Status') }}</strong></p>
            <p>
                <span class="badge">
                    @if ($project->agreement_received_at)
                        {{ __('Received') }}
                    @else
                        {{ __('Not received') }}
                    @endif
                </span>
            </p>
        </div>
    </div>
@endsection
