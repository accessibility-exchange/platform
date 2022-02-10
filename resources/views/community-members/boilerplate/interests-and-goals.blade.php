{{-- TODO: Replace with real content. --}}
<x-heading :level="$level">Goals</x-heading>
<p>Here is some information about what I would like to accomplish as a participant in accessibility planning projects.</p>
<x-heading :level="$level">Types of regulated entities that {{ $communityMember->firstName() }} is interested in</x-heading>
<ul role="list" class="tags">
    @foreach($communityMember->sectors as $sector)
    <li class="tag">{{ $sector->name }}</li>
    @endforeach
</ul>
<x-heading :level="$level">Areas within a regulated entity that {{ $communityMember->firstName() }} is interested in</x-heading>
<ul role="list" class="tags">
    @foreach($communityMember->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>

<x-heading :level="$level">Specific regulated entities that {{ $communityMember->firstName() }} is interested in</x-heading>
<ul role="list" class="tags">
    <li class="tag">Specific Federal Government Agency</li>
    <li class="tag">Specific Telecommunications Company</li>
    <li class="tag">Specific Bank</li>
</ul>
