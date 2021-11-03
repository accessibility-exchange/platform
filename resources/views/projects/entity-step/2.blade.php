<div class="flow">
    <h2>{{ __('Consultant shortlist') }}</h2>
    @if((count($project->consultants)) < $project->min)
    <p><em>{{ __('According to your project details, you need to add at least :number more consultants to your shortlist.', ['number' => $project->min - (count($project->confirmedConsultants) + count($project->requestedConsultants) + count($project->shortlistedConsultants))]) }}</em></p>
    @endif
    @if((count($project->consultants)) >= 5)
    <div class="access flow">
        <h3>{{ __('Access needs') }}</h3>
        <p><em>{{ __('An aggregated list of the consulting team’s access needs') }}</em></p>
        <ul role="list">
            @foreach($project->accessRequirements() as $requirement)
            <li>{{ $requirement }}</li>
            @endforeach
        </ul>
        <p class="align-end"><a href="{{ localized_route('collections.index') }}">{{ __('Find access providers in the Resource Hub') }}</a></p>
    </div>
    <div class="diversity flow">
        <h3>{{ __('Diversity of consultants') }}</h3>
        <div class="columns">

            <div class="column flow">
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
            <div class="column flow">
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
    @if(count($project->confirmedConsultants) > 0)
    <h3>{{ __('Confirmed') }}</h3>
    @foreach($project->confirmedConsultants as $consultant)
    <x-consultant-card :consultant="$consultant" level="4">
        <x-header :level="5">{!! __('<span class="visually-hidden">:name’s </span>Contact information', ['name' => $consultant->name]) !!}</x-header>
        <ul role="list">
            @if($consultant->phone)
            <li>{!! __('Phone: :phone', ['phone' => '<a href="tel:' . $consultant->phone_number . '">' . $consultant->phone . '</a>']) !!}</li>
            @endif
            @if($consultant->email)
            <li>{!! __('Email: :email', ['email' => '<a href="mailto:' . $consultant->email . '">' . $consultant->email . '</a>']) !!}</li>
            @endif
        </ul>
    </x-consultant-card>
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
    <x-consultant-card :consultant="$consultant" :project="$project" level="4">
        <x-slot name="actions">
            <div class="actions">
                <form action="{{ localized_route('projects.remove-consultant', $project) }}" method="post">
                    @csrf
                    @method('put')

                    <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                    <x-hearth-input type="hidden" name="status" value="requested" />

                    <x-hearth-button>{!! __('Remove <span class="visually-hidden">:name</span>', ['name' => $consultant->name]) !!}</x-hearth-button>
                </form>
                <form action="{{ localized_route('projects.update-consultant', $project) }}" method="post">
                    @csrf
                    @method('put')

                    <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                    <x-hearth-input type="hidden" name="status" value="requested" />

                    <x-hearth-button>{!! __('Request service <span class="visually-hidden">from :name</span>', ['name' => $consultant->name]) !!}</x-hearth-button>
                </form>
            </div>
        </x-slot>
    </x-consultant-card>
    @endforeach
    @if(count($project->consultants) === 0)
    <p>{{ __('Start finding consultants to add to your shortlist.') }}</p>
    <p><a class="button" href="{{ localized_route('projects.find-interested-consultants', $project) }}">{{ __('Find consultants') }}</a></p>
    @endif
</div>
