@if(count($project->engagements) > 0)
    <div class="grid">
    @foreach($project->engagements as $engagement)
    <x-engagement-card :engagement="$engagement" :level="3" />
    @endforeach
    </div>
@else
<p>{{ __('No engagements.') }}</p>
@endif
