@if(!$organization->sectors->isEmpty())
    <h3>{{ __('Sectors of interest') }}</h3>
    <ul role="list" class="tags">
        @foreach($organization->sectors as $sector)
            <li class="tag">{{ $sector->name }}</li>
        @endforeach
    </ul>
@endif
@if(!$organization->impacts->isEmpty())
    <h3>{{ __('Areas of interest') }}</h3>
    <ul role="list" class="tags">
        @foreach($organization->impacts as $impact)
            <li class="tag">{{ $impact->name }}</li>
        @endforeach
    </ul>
@endif
@if($organization->sectors->isEmpty() && $organization->impacts->isEmpty())
    <p>{{ __('No interests listed.') }}</p>
@endif
