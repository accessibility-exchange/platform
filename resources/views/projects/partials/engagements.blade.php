@if (count($project->engagements) > 0)
    <div class="grid">
        @foreach ($project->engagements as $engagement)
            <x-engagement-card :model="$engagement" :level="3" />
        @endforeach
    </div>
@else
    <p>{{ __('No engagements found.') }}</p>
@endif

@include('projects.partials.questions')
