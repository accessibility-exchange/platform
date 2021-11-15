<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            <small>{{ __('Project dashboard') }}</small><br />
            {{ $project->name }}
        </h1>
        @if($project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->format('F Y') }} <span aria-hidden="true">&middot;</span> <a href="{{ localized_route('projects.show', $project) }}">{{ __('See published project') }}</a></p>
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
        <section class="step flow" aria-labelledby="step-{{ $step }}-region">
            <h2 id="step-{{ $step }}-region">{{ $step }}. {{ $steps[$step]['title'] }}</h2>
            @isset($steps[$step]['subtitle'])
            <p class="subtitle">{{ $steps[$step]['subtitle'] }}</p>
            @endisset

            @if(count($substeps[$step]) > 0)
            <p>{{ __('You have completed :count of :total steps.', ['count' => $project->getProgress($step), 'total' => count($substeps[$step])]) }}</p>

            <progress id="step" max="100" value="{{ $project->getProgress($step) / count($substeps[$step]) * 100 }}"></progress>

            <ol role="list" class="substeps flow">
                @foreach($substeps[$step] as $substep)
                <li class="substep">
                    <p class="substep__description">
                        <a href="{{ $substep['link'] }}" aria-describedby="status-{{ $loop->iteration }}">{{ $substep['label'] }}</a>@if($substep['description'])<br />
                        {!! $substep['description'] !!}
                        @endif
                    </p>
                    <div class="substep__progress">
                        <p class="substep__progress__status badge" id="status-{{ $loop->iteration }}">
                            @if(!is_null($substeps[$step][$loop->iteration]['status']))
                            {{ ($substeps[$step][$loop->iteration]['status']) ? __('Complete') : __('In progress') }}
                            @else
                            {{ __('Not started') }}
                            @endif
                        </p>
                    </div>
                </li>
                @endforeach
            </ol>
            @endif

            @include("projects.consultant-step.$step")
            @switch($step)
            @case(1)
                    <h2>{{ __('Resources for this step') }}</h2>
                    <p><a href="#">{{ __('What to look out for in legal contracts') }}</a></p>
                    <p><a href="#">{{ __('Questions to ask your entity partners') }}</a></p>
                    @break
                @case(2)
                    <h2>{{ __('Resources for this step') }}</h2>
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
