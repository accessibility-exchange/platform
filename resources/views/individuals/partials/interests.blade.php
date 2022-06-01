

@if(!$individual->sectors->isEmpty())
<h3>{{ __('Sectors of interest') }}</h3>
<ul role="list" class="tags">
    @foreach($individual->sectors as $sector)
    <li class="tag">{{ $sector->name }}</li>
    @endforeach
</ul>
@endif
@if(!$individual->impacts->isEmpty())
<h3>{{ __('Areas of interest') }}</h3>
<ul role="list" class="tags">
    @foreach($individual->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>
@endif
