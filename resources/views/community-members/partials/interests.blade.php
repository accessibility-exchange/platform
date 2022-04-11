@can('update', $communityMember)
<x-privacy-indicator level="public">
    <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
</x-privacy-indicator>
<p><a class="button" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests') . '</span>']) !!}</a></p>
@endcan

{{-- Service preference --}}

@if(!$communityMember->sectors->isEmpty())
<x-heading :level="$level">{{ __('Types of federally regulated organizations that :name is interested in', ['name' => $communityMember->firstName()]) }}</x-heading>
<ul role="list" class="tags">
    @foreach($communityMember->sectors as $sector)
    <li class="tag">{{ $sector->name }}</li>
    @endforeach
</ul>
@endif
@if(!$communityMember->impacts->isEmpty())
<x-heading :level="$level">{{ __('Areas within a federally regulated organization that :name is interested in', ['name' => $communityMember->firstName()]) }}</x-heading>
<ul role="list" class="tags">
    @foreach($communityMember->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>
@endif

@if($communityMember->areas_of_interest)
<x-heading :level="$level">{{ __('Other areas of interest') }}</x-heading>
<x-markdown class="stack">{{ $communityMember->areas_of_interest }}</x-markdown>
@endif
