@if (!$individual->sectorsOfInterest->isEmpty())
    <h3>{{ __('Sectors of interest') }}</h3>
    <ul class="tags" role="list">
        @foreach ($individual->sectorsOfInterest as $sector)
            <li class="tag">{{ $sector->name }}</li>
        @endforeach
    </ul>
@endif
@if (!$individual->impactsOfInterest->isEmpty())
    <h3>{{ __('Areas of interest') }}</h3>
    <ul class="tags" role="list">
        @foreach ($individual->impactsOfInterest as $impact)
            <li class="tag">{{ $impact->name }}</li>
        @endforeach
    </ul>
@endif
