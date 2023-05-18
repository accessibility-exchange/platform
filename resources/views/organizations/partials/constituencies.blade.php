<h3>{{ __('Groups in the disability and Deaf community') }}</h3>

<ul class="tags" role="list">
    @if ($organization->extra_attributes->get('cross_disability_and_deaf_constituencies'))
        <li class="tag">{{ __('Any group') }}</li>
    @endif
    @foreach ($organization->disabilityAndDeafConstituencies as $constituency)
        <li class="tag">{{ $constituency->name }}</li>
    @endforeach
    @if ($organization->other_disability_constituency)
        <li class="tag">{{ $organization->other_disability_constituency }}</li>
    @endif
    @foreach ($organization->livedExperienceConstituencies as $constituency)
        <li class="tag">{{ $constituency->name }}</li>
    @endforeach
</ul>

@if ($organization->hasConstituencies('indigenousConstituencies') ||
    $organization->hasConstituencies('ethnoracialIdentityConstituencies'))
    <h3>{{ __('Ethno-racial groups') }}</h3>

    <ul class="tags" role="list">
        @foreach ($organization->indigenousConstituencies as $constituency)
            <li class="tag">{{ $constituency->name }}</li>
        @endforeach
        @foreach ($organization->ethnoracialIdentityConstituencies as $constituency)
            <li class="tag">{{ $constituency->name }}</li>
        @endforeach
        @if ($organization->other_ethnoracial)
            <li class="tag">{{ $organization->other_ethnoracial_identity_constituency }}</li>
        @endif
    </ul>
@endif

@if ($organization->hasConstituencies('genderAndSexualityConstituencies') ||
    $organization->hasConstituencies('statusConstituencies'))
    <h3>{{ __('Other identity groups') }}</h3>

    <ul class="tags" role="list">
        @foreach ($organization->statusConstituencies as $constituency)
            <li class="tag">{{ $constituency->name }}</li>
        @endforeach
        @foreach ($organization->genderAndSexualityConstituencies as $constituency)
            <li class="tag">{{ $constituency->name }}</li>
        @endforeach
    </ul>
@endif

@if ($organization->hasConstituencies('ageBracketConstituencies'))
    <h3>{{ __('Age groups') }}</h3>

    <ul class="tags" role="list">
        @foreach ($organization->ageBracketConstituencies as $constituency)
            <li class="tag">{{ $constituency->name }}</li>
        @endforeach
    </ul>
@endif

@if ($organization->languageConstituencies->count() > 0)
    <h3>{{ __('Language groups') }}</h3>

    <ul class="tags" role="list">
        @foreach ($organization->languageConstituencies as $language)
            <li class="tag">{{ $language->name }}</li>
        @endforeach
    </ul>
@endif

@if ($organization->staff_lived_experience === 'yes')
    <h3>{{ __('Staff lived experience') }}</h3>

    <p>{{ __('This organization has people on staff who have lived experience of the communities they :represent_or_serve_and_support.', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}
    </p>
@endif
