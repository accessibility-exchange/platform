@if (!$organization->sectors->isEmpty())
    <h3>{{ __('Types of regulated organizations') }}</h3>
    <x-interpretation name="{{ __('Types of regulated organizations', [], 'en') }}" />
    <ul class="tags" role="list">
        @foreach ($organization->sectors as $sector)
            <li class="tag">{{ $sector->name }}</li>
        @endforeach
    </ul>
@endif
@if (!$organization->impacts->isEmpty())
    <h3>{{ __('Areas of accessibility') }}</h3>
    <x-interpretation name="{{ __('Areas of accessibility', [], 'en') }}" />
    <ul class="tags" role="list">
        @foreach ($organization->impacts as $impact)
            <li class="tag">{{ $impact->name }}</li>
        @endforeach
    </ul>
@endif
@if ($organization->sectors->isEmpty() && $organization->impacts->isEmpty())
    <p>{{ __('No interests listed.') }}</p>
@endif
