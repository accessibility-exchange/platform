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
        <x-header :level="5">{!! __('<span class="visually-hidden">:name’s </span>Contact information', ['name' => $consultant->name]) !!}</x-header>
        <ul role="list">
            @if($consultant->phone)
            <li>{!! __('Phone: :phone', ['phone' => '<a href="tel:' . $consultant->phone_number . '">' . $consultant->phone . '</a>']) !!}</li>
            @endif
            @if($consultant->email)
            <li>{!! __('Email: :email', ['email' => '<a href="mailto:' . $consultant->email . '">' . $consultant->email . '</a>']) !!}</li>
            @endif
        </ul>
        <x-expander :level="5">
            <x-slot name="summary">{!! __('<span class="visually-hidden">:name’s </span>Access needs', ['name' => $consultant->name]) !!}</x-slot>
            <ul role="list">
                @foreach($consultant->accessSupports as $accessSupport)
                <li>{{ $accessSupport->name }}</li>
                @endforeach
            </ul>
        </x-expander>
    </x-consultant-card>
    @endforeach
    @endif
</div>
