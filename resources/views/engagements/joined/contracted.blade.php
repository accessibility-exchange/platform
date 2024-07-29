<h2>{{ __('Joined as a Community Connector') }}</h2>
<x-interpretation name="{{ __('Joined as a Community Connector', [], 'en') }}" namespace="engagements-joined" />

@if ($activeEngagements->count())
    <div class="grid">
        @foreach ($activeEngagements as $activeEngagement)
            <x-card.engagement :model="$activeEngagement" :byline=true />
        @endforeach
    </div>
@else
    <p>{{ __('No projects found.') }}</p>
@endif

@if ($completeEngagements->count())
    <x-expander level="3" :summary="__('Completed engagements')">
        <x-interpretation name="{{ __('Completed engagements', [], 'en') }}" namespace="engagements-joined" />
        <div class="grid">
            @foreach ($completeEngagements as $completeEngagement)
                <x-card.engagement :model="$completeEngagement" :byline=true />
            @endforeach
        </div>
    </x-expander>
@endif
