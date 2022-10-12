<x-card.project :project="$project" :level="4" />
@foreach ($project->engagements as $engagement)
    <x-card.engagement :model="$engagement" :level="5" />
@endforeach
