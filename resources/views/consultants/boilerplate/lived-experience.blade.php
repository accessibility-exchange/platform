{{-- TODO: Replace with real content. --}}
<x-heading :level="$level">{{ $consultant->firstName() }}’s self-description</x-heading>
<p>Here is how I describe my experience of being Deaf, of disability, and of my other intersecting identities.</p>
<x-heading :level="$level">Some aspects of {{ $consultant->firstName() }}’s identity</x-heading>
<ul role="list" class="tags">
    <li>Heard of hearing</li>
    <li>Person of colour</li>
    <li>Newcomer or immigrant</li>
</ul>
{{-- @if($consultant->birth_date) --}}
<x-heading :level="$level">{{ $consultant->firstName() }}’s age</x-heading>
<p>42</p>
{{-- @endif --}}
