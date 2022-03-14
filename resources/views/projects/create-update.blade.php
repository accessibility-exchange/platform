<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            <small>{{ __('Project dashboard') }}</small><br />
            {{ $project->name }}
        </h1>
        @if($project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }} <span aria-hidden="true">&middot;</span> <a href="{{ localized_route('projects.show', $project) }}">{{ __('See published project') }}</a></p>
        @endif
    </x-slot>
    <div class="manage">
        <section class="overview" aria-labelledby="overview">
            <h2 id="overview">{{ __('Project status') }}</h2>
            <ol role="list">
                @for ($i = 1; $i < 6; $i++)
                <li>@if($project->currentEntityStep() > $i)
                    <x-heroicon-s-check-circle width="24" height="24" />
                    <span class="visually-hidden" id="step-{{ $i }}">{{ __('completed') }}</span>
                    @elseif($project->currentEntityStep() === $i)
                    <x-progress-icon :started="true" progress="0" />
                    <span class="visually-hidden" id="step-{{ $i }}">{{ __('in progress') }}</span>
                    @else
                    <x-progress-icon :started="false" />
                    <span class="visually-hidden" id="step-{{ $i }}">{{ __('not started') }}</span>
                    @endif
                    <a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => $i]) }}" aria-describedby="step-{{ $i }}">
                        <strong>{{ $i }}. {{ $steps[$i]['title'] }}</strong>
                    </a>
                </li>
                @endfor
            </ol>
        </section>

        <section class="step stack" aria-labelledby="step">
            <h2 id="step">{{  __('Share a project update') }}</h2>
            <p>{{ __('Share a project update to share with your consulting team on the progress of your implementation.') }}</p>
            <p>{{ __('This is optional, but it is great at building trust with project participants, and increases the likelihood that they’ll come back for future consultations.') }}</p>
            <p><a href="#">{{ __('Share an update') }}</a></p>
        </section>
    </div>

</x-app-wide-layout>
