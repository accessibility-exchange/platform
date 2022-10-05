<x-card.project :project="$project" :level="4" />
@foreach ($project->engagements as $engagement)
    @if ($engagement->individual_connector_id === $user->individual?->id ||
        $engagement->organizational_connector_id === $user->organization?->id)
        <x-card.engagement :model="$engagement" :level="5" />
    @endif
@endforeach
