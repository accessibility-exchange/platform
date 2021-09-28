<x-app-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            <small>{{ __('Project dashboard') }}</small><br />
            {{ $project->name }}
        </h1>
        @if($project->started())
        <p><strong>{{ __('project.started_label') }}:</strong> {{ $project->start_date->format('F Y') }} &middot; <a href="{{ localized_route('projects.show', $project) }}">See published project</a></p>
        @endif
    </x-slot>
    <div class="manage">
        <section class="overview" aria-labelledby="overview">
            <h2 id="overview">{{ __('Project status') }}</h2>
            <ol role="list">
                @for ($i = 1; $i < 6; $i++)
                <li>@if($project->currentStep() > $i)
                    <x-heroicon-s-check-circle class="icon" width="24" height="24" />
                    @elseif($project->currentStep() === $i)
                    <x-progress-icon :started="true" :progress="$project->getProgress($i) / count($substeps[$i])" />
                    @else
                    <x-progress-icon :started="false" />
                    @endif
                    <a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => $i]) }}">
                        <strong>{{ $i }}. {{ $steps[$i] }}</strong>
                    </a>
                </li>
                @endfor
            </ol>
        </section>

        <section class="step flow" aria-labelledby="step-{{ $step }}">
            <h2 id="step-{{ $step }}">{{ $step }}. {{ $steps[$step] }}</h2>

            <p>{{ __('You have completed :count of :total steps.', ['count' => $project->getProgress($step), 'total' => count($substeps[$step])]) }}</p>

            <progress id="step" max="100" value="{{ $project->getProgress($step) / count($substeps[$step]) * 100 }}"></progress>

            <ol role="list" class="substeps flow">
                @foreach($substeps[$step] as $substep)
                <li class="substep">
                    <p class="substep__description">
                        <a href="{{ $substep['link'] }}" aria-describedby="status-{{ $loop->iteration }}">{{ $substep['label'] }}</a>@if($substep['description'])<br />
                        {{ $substep['description'] }}
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

            @if($step == 2)
            @include('projects.step.2')
            @endif
        </section>
    </div>

</x-app-layout>
