<div class="stack">
    @if((count($project->participants)) >= 5)
    <div class="access stack">
        <h2>{{ __('Access needs') }}</h2>
        <p><em>{{ __('An aggregated list of the consulting team’s access needs') }}</em></p>
        <ul role="list">
            @foreach($project->accessRequirements() as $requirement)
            <li>{{ $requirement }}</li>
            @endforeach
        </ul>
        <p class="align-end"><a href="{{ localized_route('collections.index') }}">{{ __('Find access providers in the Resource Hub') }}</a></p>
    </div>
    @endif
    @if(count($project->confirmedParticipants) > 0)
    <h3>{{ __('Participants') }}</h3>
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
        <x-expander :level="5">
            <x-slot name="summary">{!! __('<span class="visually-hidden">:name’s </span>Access needs', ['name' => $communityMember->name]) !!}</x-slot>
            <ul role="list">
                @foreach($communityMember->accessSupports as $accessSupport)
                <li>{{ $accessSupport->name }}</li>
                @endforeach
            </ul>
        </x-expander>
    </x-community-member-card>
    @endforeach
    @endif
</div>
