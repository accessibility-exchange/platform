<div class="flow">
    <h2>{{ __('Consultant shortlist') }}</h2>
    @if(count($project->confirmedConsultants) > 0)
    <h3>{{ __('Confirmed') }}</h3>
    @foreach($project->confirmedConsultants as $consultant)
    <x-consultant-card :consultant="$consultant" level="4"></x-consultant-card>
    @endforeach
    @endif
    @if(count($project->requestedConsultants) > 0)
    <h3>{{ __('Awaiting response') }}</h3>
    @foreach($project->requestedConsultants as $consultant)
    <x-consultant-card :consultant="$consultant" level="4"></x-consultant-card>
    @endforeach
    @endif
    @if(count($project->shortlistedConsultants) > 0)
    <h3>{{ __('Shortlisted') }}</h3>
    @endif
    @foreach($project->shortlistedConsultants as $consultant)
    <x-consultant-card :consultant="$consultant" level="4">
        <x-slot name="actions">
            <div class="actions">
                <form action="{{ localized_route('projects.remove-consultant', $project) }}" method="post">
                    @csrf
                    @method('put')

                    <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                    <x-hearth-input type="hidden" name="status" value="requested" />

                    <x-hearth-button>{!! __('Remove <span class="visually-hidden">from :name</span>', ['name' => $consultant->name]) !!}</x-hearth-button>
                </form>
                <form action="{{ localized_route('projects.update-consultant', $project) }}" method="post">
                    @csrf
                    @method('put')

                    <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                    <x-hearth-input type="hidden" name="status" value="requested" />

                    <x-hearth-button>{!! __('Request service <span class="visually-hidden">:name</span>', ['name' => $consultant->name]) !!}</x-hearth-button>
                </form>
            </div>
        </x-slot>
    </x-consultant-card>
    @endforeach
    @if(count($project->confirmedConsultants) === 0 && count($project->requestedConsultants) === 0 && count($project->shortlistedConsultants) === 0)
    <p>{{ __('Start finding consultants to add to your shortlist.') }}</p>
    <p><a class="button" href="{{ localized_route('projects.edit-consultants', $project) }}">{{ __('Find consultants') }}</a></p>
    @endif
</div>
