<x-card.project :model="$project" :level="4" />
@foreach ($project->engagements as $engagement)
    @if ($engagement->participants->contains($user->individual))
        <x-card.engagement :model="$engagement" :level="5" />
    @endif
@endforeach
