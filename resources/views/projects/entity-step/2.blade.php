<div class="stack">
    <h2>{{ __('Participant shortlist') }}</h2>
    @if((count($project->participants)) < $project->min)
    <p><em>{{ __('According to your project details, you need to add at least :number more participants to your shortlist.', ['number' => $project->min - (count($project->confirmedParticipants) + count($project->requestedParticipants) + count($project->shortlistedParticipants))]) }}</em></p>
    @endif
    @if((count($project->participants)) >= 5)
    <div class="access stack">
        <h3>{{ __('Access needs') }}</h3>
        <p><em>{{ __('An aggregated list of the consulting team’s access needs') }}</em></p>
        <ul role="list">
            @foreach($project->accessRequirements() as $requirement)
            <li>{{ $requirement }}</li>
            @endforeach
        </ul>
        <p class="align-end"><a href="{{ localized_route('collections.index') }}">{{ __('Find access providers in the Resource Hub') }}</a></p>
    </div>
    <div class="diversity stack">
        <h3>{{ __('Diversity of participants') }}</h3>
        <div class="columns">

            <div class="column stack">
                <h4>{{ __('On your shortlist:') }}</h4>
                <p><strong>{{ __('Lived experience') }}</strong></p>
                <ul>
                    @foreach ($project->presentLivedExperiences() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <p><strong>{{ __('Communities') }}</strong></p>
                <ul>
                    @foreach ($project->presentCommunities() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @if($project->regions && count($project->regions) === 13)
                <p><strong>{{ __('Cross-country') }}</strong></p>
                <ul>
                    @foreach ($project->presentRegions() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="column stack">
                <h4>{{ __('Missing from your shortlist:') }}</h4>
                <p><strong>{{ __('Lived experience') }}</strong></p>
                <ul>
                    @foreach ($project->absentLivedExperiences() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <p><strong>{{ __('Communities') }}</strong></p>
                <ul>
                    @foreach ($project->absentCommunities() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @if($project->regions && count($project->regions) === 13)
                <p><strong>{{ __('Cross-country') }}</strong></p>
                <ul>
                    @foreach ($project->absentRegions() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
    @endif
    @if(count($project->confirmedParticipants) > 0)
    <h3>{{ __('Confirmed') }}</h3>
    @foreach($project->confirmedParticipants as $communityMember)
    <x-community-member-card :communityMember="$communityMember" level="4">
        <x-heading :level="5">{!! __('<span class="visually-hidden">:name’s </span>Contact information', ['name' => $communityMember->name]) !!}</x-heading>
        <ul role="list">
            @if($communityMember->phone)
            <li>{!! __('Phone: :phone', ['phone' => '<a href="tel:' . $communityMember->phone_number . '">' . $communityMember->phone . '</a>']) !!}</li>
            @endif
            @if($communityMember->email)
            <li>{!! __('Email: :email', ['email' => '<a href="mailto:' . $communityMember->email . '">' . $communityMember->email . '</a>']) !!}</li>
            @endif
        </ul>
    </x-community-member-card>
    @endforeach
    @endif
    @if(count($project->requestedParticipants) > 0)
    <h3>{{ __('Awaiting response') }}</h3>
    @foreach($project->requestedParticipants as $communityMember)
    <x-community-member-card :communityMember="$communityMember" level="4"></x-community-member-card>
    @endforeach
    @endif
    @if(count($project->shortlistedParticipants) > 0)
    <h3>{{ __('Shortlisted') }}</h3>
    @endif
    @foreach($project->shortlistedParticipants as $communityMember)
    <x-community-member-card :communityMember="$communityMember" :project="$project" level="4">
        <x-slot name="actions">
            <div class="actions">
                <form action="{{ localized_route('projects.remove-participant', $project) }}" method="post">
                    @csrf
                    @method('put')

                    <x-hearth-input type="hidden" name="participant_id" :value="$communityMember->id" />
                    <x-hearth-input type="hidden" name="status" value="requested" />

                    <x-hearth-button>{!! __('Remove <span class="visually-hidden">:name</span>', ['name' => $communityMember->name]) !!}</x-hearth-button>
                </form>
                <form action="{{ localized_route('projects.update-participant', $project) }}" method="post">
                    @csrf
                    @method('put')

                    <x-hearth-input type="hidden" name="participant_id" :value="$communityMember->id" />
                    <x-hearth-input type="hidden" name="status" value="requested" />

                    <x-hearth-button>{!! __('Request service <span class="visually-hidden">from :name</span>', ['name' => $communityMember->name]) !!}</x-hearth-button>
                </form>
            </div>
        </x-slot>
    </x-community-member-card>
    @endforeach
    @if(count($project->participants) === 0)
    <p>{{ __('Start finding community members to add to your shortlist.') }}</p>
    <p><a class="button" href="{{ localized_route('projects.find-interested-participants', $project) }}">{{ __('Find participants') }}</a></p>
    @endif
</div>
