@if(count($project->reviews) > 0)
<x-heading :level="$level">{{ __('Overall score') }}</x-heading>
<div class="scores stack">
    <div class="scores__score">
        <span class="score__attribute">{{ __('Met access needs') }}</span>
        <span class="score__value"><x-dots class="score__indicator" :value="$project->averageRatingFor('met_access_needs')" /> {{ $project->averageRatingFor('met_access_needs') }}/5</span>
    </div>
    <div class="scores__score">
        <span class="score__attribute">{{ __('Open to feedback') }}</span>
        <span class="score__value"><x-dots class="score__indicator" :value="$project->averageRatingFor('open_to_feedback')" /> {{ $project->averageRatingFor('open_to_feedback') }}/5</span>
    </div>
    <div class="scores__score">
        <span class="score__attribute">{{ __('Kind and patient') }}</span>
        <span class="score__value"><x-dots class="score__indicator" :value="$project->averageRatingFor('kind_and_patient')" /> {{ $project->averageRatingFor('kind_and_patient') }}/5</span>
    </div>
    <div class="scores__score">
        <span class="score__attribute">{{ __('Valued input') }}</span>
        <span class="score__value"><x-dots class="score__indicator" :value="$project->averageRatingFor('valued_input')" /> {{ $project->averageRatingFor('valued_input') }}/5</span>
    </div>
    <div class="scores__score">
        <span class="score__attribute">{{ __('Respectful of identity') }}</span>
        <span class="score__value"><x-dots class="score__indicator" :value="$project->averageRatingFor('respectful_of_identity')" /> {{ $project->averageRatingFor('respectful_of_identity') }}/5</span>
    </div>
    <div class="scores__score">
        <span class="score__attribute">{{ __('Sensitive to comfort levels') }}</span>
        <span class="score__value"><x-dots class="score__indicator" :value="$project->averageRatingFor('sensitive_to_comfort_levels')" /> {{ $project->averageRatingFor('sensitive_to_comfort_levels') }}/5</span>
    </div>
    <div class="scores__score">
        <span class="score__attribute">{{ __('Participant retention') }}</span>
        <span class="score__value">{{ $project->participantRetention() * 100 }}%</span>
    </div>
</div>
<x-heading :level="$level">{{ __('Individual experiences') }}</x-heading>
<ul role="list" class="comments grid">
    @foreach($project->reviews as $review)
    <li class="comments__comment">“{{ $review->body }}”</li>
    @endforeach
</ul>
@else
<p>{{ __('No community experiences have been recorded for this project.') }}</p>
@endif
