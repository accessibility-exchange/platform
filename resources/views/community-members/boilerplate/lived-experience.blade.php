{{-- TODO: Replace with real content. --}}
<x-heading :level="$level">{{ $communityMember->firstName() }}’s self-description</x-heading>
<p>Here is how I describe my experience of being Deaf, of disability, and of my other intersecting identities.</p>
<x-heading :level="$level">Some aspects of {{ $communityMember->firstName() }}’s identity</x-heading>
<ul role="list" class="tags">
    <li class="tag">Heard of hearing</li>
    <li class="tag">Person of colour</li>
    <li class="tag">Newcomer or immigrant</li>
</ul>
{{-- @if($communityMember->birth_date) --}}
<x-heading :level="$level">{{ $communityMember->firstName() }}’s age</x-heading>
<p>42</p>
{{-- @endif --}}
