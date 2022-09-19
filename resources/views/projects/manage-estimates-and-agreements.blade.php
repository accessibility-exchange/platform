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
                    <p><span class="badge badge--status">{{ __('Approved') }}</span></p>
                @elseif($project->estimate_returned_at)
                    <p><span class="badge badge--status">{{ __('Returned') }}</span></p>
                @else
                    <p><span class="badge badge--status">{{ __('Pending') }}</span></p>
                    <p>{{ __('You sent this request on :date.', ['date' => $project->estimate_requested_at->translatedFormat('F j, Y')]) }}
                    </p>
                    <p><strong>{{ __('This estimate includes the following engagements:') }}</strong></p>
                    <ul>
                        @forelse($engagements as $engagement)
                            <li>{{ $engagement->name }}</li>
                        @empty
                            <li>{{ __('None found.') }}</li>
                        @endforelse
                    </ul>
                @endif
            @else
                <p>
                    <span class="badge badge--status">
                        {{ __('Not yet requested') }}
                    </span>
                </p>
                <h4>{{ __('New estimate request') }}</h4>
                <p><strong>{{ __('This estimate includes the following engagements:') }}</strong></p>
                <ul>
                    @forelse($engagements as $engagement)
                        <li>{{ $engagement->name }}</li>
                    @empty
                        <li>{{ __('None found.') }}</li>
                    @endforelse
                </ul>
                <x-hearth-alert class="bg-grey-1" x-show="true" :dismissable="false" :title="__('Missing an engagement?')">
                    <p>{{ __('To include an engagement in a quote request, you must have filled out the engagement invitation.') }}
                    </p>
                </x-hearth-alert>
                <livewire:estimate-requester :model="$project" />
            @endif
        </div>
        <div class="stack w-full md:w-1/2">
            <h3>{{ __('Agreements') }}</h3>
            <p>{!! __('The agreement will be sent with your estimate. Please sign this agreement and send it to :email.', [
                'email' => Str::inlineMarkdown('<' . settings()->get('email', 'support@accessibilityexchange.ca') . '>'),
            ]) !!}</p>
            <p><strong>{{ __('Status') }}</strong></p>
            <p>
                <span class="badge badge--status">
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
