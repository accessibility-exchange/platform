
@if($communityMember->lived_experience)
<h3>{{ __('Lived experience') }}</h3>
@markdown
{{ $communityMember->lived_experience }}
@endmarkdown
@endif

@if($communityMember->skills_and_strengths)
    <h3>{{ __('Skills and strengths') }}</h3>
    @markdown
    {{ $communityMember->skills_and_strengths }}
    @endmarkdown
@endif

@if(count($communityMember->relevant_experiences) > 0)
    <h3>{{ __('Relevant experience') }}</h3>
    <p>{{ __('Relevant work and volunteer experience.') }}</p>

    @foreach($communityMember->relevant_experiences as $experience)
        <h4>{{ $experience['title'] }}</h4>
        <p>{{ $experience['organization'] }}<br />
            {{ $experience['start_year'] }}&ndash;{{ $experience['current'] ? __('present') : $experience['end_year'] }}
        </p>
    @endforeach
@endif
