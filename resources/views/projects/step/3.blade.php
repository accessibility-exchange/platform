<div class="flow">
    @if((count($project->consultants)) >= 5)
    <div class="access flow">
        <h2>{{ __('Access needs') }}</h2>
        <p><em>{{ __('An aggregated list of your consultants’ access needs') }}</em></p>
        <ul role="list">
            @foreach($project->accessRequirements() as $requirement)
            <li>{{ $requirement }}</li>
            @endforeach
        </ul>
        <p class="align-end"><a href="{{ localized_route('collections.index') }}">{{ __('Find access providers in the Resource Hub') }}</a></p>
    </div>
    @endif
    @if(count($project->confirmedConsultants) > 0)
    <h3>{{ __('Consultants') }}</h3>
    @foreach($project->confirmedConsultants as $consultant)
    <x-consultant-card :consultant="$consultant" level="4">
        <x-header :level="4">{{ __('Contact information') }}</x-header>
        <ul role="list">
            @if($consultant->phone)
            <li>{{ __('Phone: :phone', ['phone' => $consultant->phone])}}</li>
            @endif
            @if($consultant->email)
            <li>{{ __('Email: :email', ['email' => $consultant->email])}}</li>
            @endif
        </ul>
        <details>
            <summary>{{ __('Access Needs') }}</summary>
            <ul role="list">
                @foreach($consultant->accessSupports as $accessSupport)
                <li>{{ $accessSupport->name }}</li>
                @endforeach
            </ul>
        </details>
    </x-consultant-card>
    @endforeach
    @endif
</div>
