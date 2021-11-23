{{-- TODO: Replace with real content. --}}
<x-heading :level="$level">Goals</x-heading>
<p>Here is some information about what I would like to accomplish as a consultant.</p>
<x-heading :level="$level">Types of regulated entities that {{ $consultant->firstName() }} is interested in</x-heading>
<ul role="list" class="tags">
    @foreach($consultant->sectors as $sector)
    <li>{{ $sector->name }}</li>
    @endforeach
</ul>
<x-heading :level="$level">Areas within a regulated entity that {{ $consultant->firstName() }} is interested in</x-heading>
<ul role="list" class="tags">
    @foreach($consultant->impacts as $impact)
    <li>{{ $impact->name }}</li>
    @endforeach
</ul>

<x-heading :level="$level">Specific regulated entities that {{ $consultant->firstName() }} is interested in</x-heading>
<ul role="list" class="tags">
    <li>Specific Federal Government Agency</li>
    <li>Specific Telecommunications Company</li>
    <li>Specific Bank</li>
</ul>
