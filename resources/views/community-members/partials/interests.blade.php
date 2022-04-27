

@if(!$communityMember->sectors->isEmpty())
<h3>{{ __('Sectors of interest') }}</h3>
<ul role="list" class="tags">
    @foreach($communityMember->sectors as $sector)
    <li class="tag">{{ $sector->name }}</li>
    @endforeach
</ul>
@endif
@if(!$communityMember->impacts->isEmpty())
<h3>{{ __('Areas of interest') }}</h3>
<ul role="list" class="tags">
    @foreach($communityMember->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>
@endif
