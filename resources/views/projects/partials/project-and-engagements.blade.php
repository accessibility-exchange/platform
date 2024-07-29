<x-card.project :model="$project" :level="3" />
@if (Auth::user()->can('manage', $project))
    @foreach ($project->allEngagements as $engagement)
        <x-card.engagement :model="$engagement" :level="4" />
    @endforeach
@else
    @foreach ($project->engagements as $engagement)
        <x-card.engagement :model="$engagement" :level="5" />
    @endforeach
@endif
