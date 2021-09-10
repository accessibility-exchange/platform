{{-- TODO: Replace with real content. --}}
<x-header :level="$level">Goals</x-header>
<p>Here is some information about what I would like to accomplish as a consultant.</p>
<x-header :level="$level">Types of regulated entities that {{ $consultant->firstName() }} is interested in</x-header>
<ul role="list" class="tags">
    <li>Telecommunications</li>
    <li>Banking</li>
    <li>Parks and recreation</li>
</ul>
<x-header :level="$level">Areas within a regulated entity that {{ $consultant->firstName() }} is interested in</x-header>
<ul role="list" class="tags">
    <li>Employment</li>
    <li>Built environment</li>
    <li>Transportation</li>
</ul>

<x-header :level="$level">Specific regulated entities that {{ $consultant->firstName() }} is interested in</x-header>
<ul role="list" class="tags">
    <li>Specific Federal Government Agency</li>
    <li>Specific Telecommunications Company</li>
    <li>Specific Bank</li>
</ul>
