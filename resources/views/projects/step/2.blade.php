<div class="flow">
    <h2>{{ __('Consultant shortlist') }}</h2>
    @if(count($project->confirmedConsultants) > 0)
    <h3>{{ __('Confirmed') }}</h3>
    @foreach($project->confirmedConsultants as $consultant)
    <p><a href="{{ localized_route('consultants.show', $consultant) }}">{{ $consultant->name }}</a></p>
    @endforeach
    @endif
    @if(count($project->requestedConsultants) > 0)
    <h3>{{ __('Pending response') }}</h3>
    @foreach($project->requestedConsultants as $consultant)
    <p><a href="{{ localized_route('consultants.show', $consultant) }}">{{ $consultant->name }}</a></p>
    @endforeach
    @endif
    @if(count($project->shortlistedConsultants) > 0)
    <h3>{{ __('Selected') }}</h3>
    @endif
    @foreach($project->shortlistedConsultants as $consultant)
    <p><a href="{{ localized_route('consultants.show', $consultant) }}">{{ $consultant->name }}</a></p>
    <form action="{{ localized_route('projects.remove-consultant', $project) }}" method="post">
        @csrf
        @method('put')

        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
        <x-hearth-input type="hidden" name="status" value="requested" />

        <x-hearth-button>{{ __('Remove') }}</x-hearth-button>
    </form>
    <form action="{{ localized_route('projects.update-consultant', $project) }}" method="post">
        @csrf
        @method('put')

        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
        <x-hearth-input type="hidden" name="status" value="requested" />

        <x-hearth-button>{{ __('Request service') }}</x-hearth-button>
    </form>
    @endforeach
    @if(count($project->confirmedConsultants) === 0 && count($project->requestedConsultants) === 0 && count($project->shortlistedConsultants) === 0)
    <p>{{ __('Start finding consultants to add to your shortlist.') }}</p>
    @endif
</div>
