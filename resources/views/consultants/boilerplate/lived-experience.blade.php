{{-- TODO: Replace with real content. --}}
<x-header :level="$level">{{ $consultant->firstName() }}’s self-description</x-header>
<p>Here is how I describe my experience of Deafness, disability, and other intersecting identities.</p>
<x-header :level="$level">Some aspects of {{ $consultant->firstName() }}’s identity</x-header>
<ul role="list" class="tags">
    <li>Heard of hearing</li>
    <li>Person of colour</li>
    <li>Newcomer or immigrant</li>
</ul>
{{-- @if($consultant->birth_date) --}}
<x-header :level="$level">{{ $consultant->firstName() }}’s age</x-header>
<p>42</p>
{{-- @endif --}}
