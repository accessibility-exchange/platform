<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            <small>{{ __('Project dashboard') }}</small><br />
            {{ $project->name }}
        </h1>
        @if($project->started())
        <p><strong>{{ __('project.started_label') }}:</strong> {{ $project->start_date->format('F Y') }} <span aria-hidden="true">&middot;</span> <a href="{{ localized_route('projects.show', $project) }}">{{ __('See published project') }}</a></p>
        @endif
    </x-slot>
    <div class="manage">
        <section class="overview flow" aria-labelledby="overview">
            <h2 id="overview">{{ __('Project status') }}</h2>
            <ol role="list">
                @for ($i = 1; $i < 4; $i++)
                <li>@if($project->currentConsultantStep() > $i)
                    <x-heroicon-s-check-circle class="icon" width="24" height="24" aria-hidden="true" />
                    <span class="visually-hidden" id="step-{{ $i }}">{{ __('completed') }}</span>
                    @elseif($project->currentConsultantStep() === $i)
                    <x-progress-icon :started="true" progress="0" />
                    <span class="visually-hidden" id="step-{{ $i }}">{{ __('in progress') }}</span>
                    @else
                    <x-progress-icon :started="false" />
                    <span class="visually-hidden" id="step-{{ $i }}">{{ __('not started') }}</span>
                    @endif
                    <a href="{{ localized_route('projects.participate', ['project' => $project, 'step' => $i]) }}" aria-describedby="step-{{ $i }}" @if($i == $step) aria-current="page" @endif>
                        <strong>{{ $i }}. {{ $steps[$i]['title'] }}</strong>
                    </a>
                </li>
                @endfor
            </ol>
        </section>
        <section class="step flow" aria-labelledby="step-{{ $step }}">
            <h2 id="step-{{ $step }}">{{ $step }}. {{ $steps[$step]['title'] }}</h2>
            @isset($steps[$step]['subtitle'])
            <p class="subtitle">{{ $steps[$step]['subtitle'] }}</p>
            @endisset

            @include("projects.consultant-step.$step")
            <h2>{{ __('Resources for this step') }}</h2>
            @switch($step)
                @case(1)
                    <p><a href="#">{{ __('What to look out for in legal contracts') }}</a></p>
                    <p><a href="#">{{ __('Questions to ask your entity partners') }}</a></p>
                    @break
                @case(2)
                    <p><a href="#">{{ __('How to advocate for your needs') }}</a></p>
                    <p><a href="#">{{ __('Communication tips for working with entities') }}</a></p>
                    @break
                @default
            @endswitch
            <h2>{{ __('Need support?') }}</h2>
            <p>{{ __('If you feel like you are running into an issue with your entity partners and would like some help solving them, please contact our support team.') }}</p>
            <p><a href="#">{{ __('Get support') }} <span aria-hidden="true">&rarr;</span></a></p>
        </section>
    </div>

</x-app-wide-layout>
