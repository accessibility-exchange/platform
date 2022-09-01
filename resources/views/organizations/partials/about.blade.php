<h3>{{ __('About the organization') }}</h3>

@markdown{{ $organization->getWrittenTranslation('about', $language) }}@endmarkdown

<h3>{{ __('Type of organization') }}</h3>

<p>
    <strong>{{ Str::ucfirst(__('organization.types.' . $organization->type . '.name')) }}</strong><br />
    {{ __('organization.types.' . $organization->type . '.description') }}
</p>

<h3>{{ __('Where we operate') }}</h3>

<h4>{{ __('Regions') }}</h4>
<ul class="tags" role="list">
    @foreach ($organization->service_regions as $region)
        <li class="tag">{{ $region }}</li>
    @endforeach
</ul>

<h4>{{ __('Urban, rural, or remote') }}</h4>
<ul class="tags" role="list">
    @foreach ($organization->areaTypes as $areaType)
        <li class="tag">
            {{ $areaType->name }}
        </li>
    @endforeach
</ul>

<h3>{{ __('Working languages') }}</h3>

<ul class="tags" role="list">
    @foreach ($organization->working_languages as $code)
        <li class="tag">{{ get_language_exonym($code, locale()) }}</li>
    @endforeach
</ul>

@if ($organization->isConsultant())
    <h3>{{ __('Consulting services') }}</h3>
    <p>{{ __('As an Accessibility Consultant, we can help with:') }}</p>
    <ul>
        @foreach ($organization->consulting_services as $service)
            <li>{{ __('consulting-services.' . $service) }}</li>
        @endforeach
    </ul>
@endif
