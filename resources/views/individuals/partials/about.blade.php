

@markdown
{{ $individual->bio }}
@endmarkdown

<h3>{{ __('Languages :name uses', ['name' => $individual->first_name]) }}</h3>

<ul>
    @foreach($individual->all_languages as $language)
        <li>{{ get_language_exonym($language) }}</li>
    @endforeach
</ul>

@if($individual->isConnector())

<h3>{{ __('Communities :name is connected to', ['name' => $individual->firstName]) }}</h3>

<h4>{{ __('Disability or Deaf communities') }}</h4>

<ul role="list" class="tags">
    @foreach($individual->livedExperienceConnections as $connection)
        <li class="tag">{{ $connection->name }}</li>
    @endforeach
    @if($individual->other_lived_experience_connections)
        <li class="tag">{{ $individual->other_lived_experience_connections }}</li>
    @endif
</ul>

@if($individual->constituencyConnections->isNotEmpty() || $individual->other_constituency_connections)
<h4>{{ __('Other intersectional communities') }}</h4>

<ul role="list" class="tags">
    @foreach($individual->constituencyConnections as $connection)
        <li class="tag">{{ $connection->name }}</li>
    @endforeach
    @if($individual->other_constituency_connections)
        <li class="tag">{{ $individual->other_constituency_connections }}</li>
    @endif
</ul>
@endif

@if($individual->ageBracketConnections->isNotEmpty())
<h4>{{ __('Age groups') }}</h4>

<ul role="list" class="tags">
    @foreach($individual->ageBracketConnections as $connection)
        <li class="tag">{{ $connection->name }}</li>
    @endforeach
</ul>
@endif
@endif
