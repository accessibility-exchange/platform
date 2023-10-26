<h3>{{ __('About the organization') }}</h3>
<x-interpretation name="{{ __('About the organization', [], 'en') }}" />

{{ $organization->getWrittenTranslation('about', $language) }}

<h3>{{ __('Type of organization') }}</h3>
<x-interpretation name="{{ __('Type of organization', [], 'en') }}" />

<p>
    <strong>{{ App\Enums\OrganizationType::labels()[$organization->type] }}</strong><br />
    {{ App\Enums\OrganizationType::tryFrom($organization->type)->description() }}
</p>

<h3>{{ __('Where we operate') }}</h3>
<x-interpretation name="{{ __('Where we operate', [], 'en') }}" />

<h4>{{ __('Regions') }}</h4>
<x-interpretation name="{{ __('Regions', [], 'en') }}" />
<ul class="tags" role="list">
    @foreach ($organization->display_service_areas as $region)
        <li class="tag">{{ $region }}</li>
    @endforeach
</ul>

<h4>{{ __('Urban, rural, or remote') }}</h4>
<x-interpretation name="{{ __('Urban, rural, or remote', [], 'en') }}" />
<ul class="tags" role="list">
    @foreach ($organization->areaTypeConstituencies as $areaType)
        <li class="tag">
            {{ $areaType->name }}
        </li>
    @endforeach
</ul>

<h3>{{ __('Working languages') }}</h3>
<x-interpretation name="{{ __('Working languages', [], 'en') }}" />

<ul class="tags" role="list">
    @foreach ($organization->working_languages as $code)
        <li class="tag">{{ get_language_exonym($code, locale()) }}</li>
    @endforeach
</ul>

@if ($organization->isConsultant())
    <h3>{{ __('Consulting services') }}</h3>
    <x-interpretation name="{{ __('Consulting services', [], 'en') }}" />
    <p>{{ __('As an Accessibility Consultant, we can help with:') }}</p>
    <ul>
        @foreach ($organization->consulting_services as $service)
            <li>{{ App\Enums\ConsultingService::labels()[$service] }}</li>
        @endforeach
    </ul>
@endif
