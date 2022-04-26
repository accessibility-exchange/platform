

<x-markdown class="stack">{{ $communityMember->bio }}</x-markdown>

<h3>{{ __('Languages :name uses', ['name' => $communityMember->first_name]) }}</h3>

<ul>
    @foreach($communityMember->all_languages as $language)
        <li>{{ get_locale_name($language) }}</li>
    @endforeach
</ul>

@if($communityMember->isConnector())

<h3>{{ __('Communities :name is connected to', ['name' => $communityMember->firstName]) }}</h3>

<h4>{{ __('Disability or Deaf communities') }}</h4>

<ul role="list" class="tags">
    @foreach($communityMember->livedExperienceConnections as $connection)
        <li class="tag">{{ $connection->name }}</li>
    @endforeach
    @if($communityMember->other_lived_experience_connections)
        <li class="tag">{{ $communityMember->other_lived_experience_connections }}</li>
    @endif
</ul>

@if($communityMember->communityConnections->isNotEmpty() || $communityMember->other_community_connections)
<h4>{{ __('Other intersectional communities') }}</h4>

<ul role="list" class="tags">
    @foreach($communityMember->communityConnections as $connection)
        <li class="tag">{{ $connection->name }}</li>
    @endforeach
    @if($communityMember->other_community_connections)
        <li class="tag">{{ $communityMember->other_community_connections }}</li>
    @endif
</ul>
@endif

@if($communityMember->ageGroupConnections->isNotEmpty())
<h4>{{ __('Age groups') }}</h4>

<ul role="list" class="tags">
    @foreach($communityMember->ageGroupConnections as $connection)
        <li class="tag">{{ $connection->name }}</li>
    @endforeach
</ul>
@endif
@endif
