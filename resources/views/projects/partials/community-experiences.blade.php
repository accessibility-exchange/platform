<x-header :level="$level">{{ __('Overall score') }}</x-header>
<p>{{ __('Met access needs') }} {{ $project->averageRatingFor('met_access_needs') }}/5</p>
<p>{{ __('Open to feedback') }} {{ $project->averageRatingFor('open_to_feedback') }}/5</p>
<p>{{ __('Kind and patient') }} {{ $project->averageRatingFor('kind_and_patient') }}/5</p>
<p>{{ __('Valued input') }} {{ $project->averageRatingFor('valued_input') }}/5</p>
<p>{{ __('Respectful of identity') }} {{ $project->averageRatingFor('respectful_of_identity') }}/5</p>
<p>{{ __('Sensitive to comfort levels') }} {{ $project->averageRatingFor('sensitive_to_comfort_levels') }}/5</p>
<p>{{ __('Consultant retention') }} {{ $project->consultantRetention() * 100 }}%</p>
<x-header :level="$level">{{ __('Individual experiences') }}</x-header>
<div class="comments flow">
    @foreach($project->reviews as $review)
    <p class="comments__comment">{{ $review->body }}</p>
    @endforeach
</div>
