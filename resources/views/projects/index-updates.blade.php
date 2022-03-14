
<x-app-wide-layout>
    <x-slot name="title">{{ __('Updates for :project', ['project' => $project->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Updates for :project', ['project' => $project->name]) }}
        </h1>
        @if($project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @else
        <p><strong>{{ __('Starting') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @endif
        @if($project->completed())
        <p><strong>{{ __('Completed') }}:</strong> {{ $project->end_date->translatedFormat('F Y') }}</p>
        @endif
        @can('manage', $project)
        <p><a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => 5]) }}">{{ __('Return to project dashboard') }}</a></p>
        @elsecan('participate', $project)
        <p><a href="{{ localized_route('projects.participate', ['project' => $project, 'step' => 3]) }}">{{ __('Return to project dashboard') }}</a></p>
        @endcan
    </x-slot>

    <div class="box stack">
        <article class="update stack">
            <h2>{{ __('Update: October 15, 2021') }}</h2>
            <p>{{ __('A brief excerpt from this update.') }}
                <p><a href="#">{{  __('Read more') }} <span class="visually-hidden">– {{ __('Update: October 15, 2021') }}</span></a></p>
        </article>
        <article class="update stack">
            <h2>{{ __('Update: November 1, 2021') }}</h2>
            <p>{{ __('A brief excerpt from this update.') }}
                <p><a href="#">{{  __('Read more') }} <span class="visually-hidden">– {{ __('Update: November 1, 2021') }}</span></a></p>
        </article>
    </div>
</x-app-wide-layout>
