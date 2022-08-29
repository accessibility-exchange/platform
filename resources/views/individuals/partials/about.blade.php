@markdown
    {{ $individual->getWrittenTranslation('bio', $language) }}
@endmarkdown

<h3>
    {{ __('Languages :name uses', ['name' => $individual->first_name]) }}</h3>

<ul>
    @foreach ($individual->working_languages as $language)
        <li>{{ get_language_exonym($language) }}</li>
    @endforeach
</ul>

@if ($individual->isConsultant())
    <h3>{{ __('As an Accessibility Consultant, :name can help with:', ['name' => $individual->firstName]) }}</h3>
    <ul>
        @foreach ($individual->consulting_services as $service)
            <li>{{ __('consulting-services.' . $service) }}</li>
        @endforeach
    </ul>
@endif

@if ($individual->isConnector())
    <h3 class="repel">{{ __('As a Community Connector, :name can connect to:', ['name' => $individual->firstName]) }}
        @can('update', $individual)
            <p><a class="cta secondary"
                    href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]) }}">{!! __('Edit :section', [
                        'section' => '<span class="visually-hidden">' . __('groups you can connect to') . '</span>',
                    ]) !!}</a>
            </p>
        @endcan
    </h3>

    <h4>{{ __('Groups in the disability and Deaf community') }}</h4>

    <ul class="tags" role="list">
        @foreach ($individual->livedExperienceConnections as $livedExperience)
            @if ($livedExperience->getTranslation('name', 'en') !== 'People who experience disabilities')
                <li class="tag">{{ $livedExperience->name }}</li>
            @endif
        @endforeach
        @foreach ($individual->disabilityTypeConnections as $disabilityType)
            <li class="tag">{{ $disabilityType->name }}</li>
        @endforeach
        @if ($individual->other_disability_type_connection)
            <li class="tag">{{ $individual->other_disability_type_connection }}</li>
        @endif
    </ul>

    @if ($individual->extra_attributes->has_indigenous_identities ||
        $individual->extra_attributes->has_ethnoracial_identities)
        <h4>{{ __('Ethno-racial groups') }}</h4>

        <ul class="tags" role="list">
            @foreach ($individual->indigenousIdentityConnections as $indigenousIdentity)
                <li class="tag">{{ $indigenousIdentity->name }}</li>
            @endforeach
            @foreach ($individual->ethnoracialIdentityConnections as $ethnoracialIdentity)
                <li class="tag">{{ $ethnoracialIdentity->name }}</li>
            @endforeach
            @if ($individual->other_ethnoracial_identity_connection)
                <li class="tag">{{ $individual->other_ethnoracial_identity_connection }}</li>
            @endif
        </ul>
    @endif

    @if ($individual->extra_attributes->has_gender_and_sexual_identities ||
        $individual->extra_attributes->has_refugee_and_immigrant_constituency)
        <h3>{{ __('Other identity groups') }}</h3>

        <ul class="tags" role="list">
            @if ($individual->extra_attributes->has_refugee_and_immigrant_constituency)
                <li class="tag">{{ __('Refugees and/or immigrants') }}</li>
            @endif
            @foreach ($individual->genderIdentityConnections as $genderIdentity)
                <li class="tag">{{ $genderIdentity->name_plural }}</li>
            @endforeach
            @if ($individual->constituencyConnections->contains($transPeople))
                <li class="tag">{{ $transPeople->name_plural }}</li>
            @endif
            @if ($individual->constituencyConnections->contains($twoslgbtqiaplusPeople))
                <li class="tag">{{ $twoslgbtqiaplusPeople->name_plural }}</li>
            @endif
        </ul>
    @endif

    @if ($individual->extra_attributes->has_age_brackets)
        <h3>{{ __('Age groups') }}</h3>

        <ul class="tags" role="list">
            @foreach ($individual->ageBracketConnections as $ageBracket)
                <li class="tag">{{ $ageBracket->name }}</li>
            @endforeach
        </ul>
    @endif

    @if ($individual->languageConnections->count() > 0)
        <h3>{{ __('Language groups') }}</h3>

        <ul class="tags" role="list">
            @foreach ($individual->languageConnections as $language)
                <li class="tag">{{ $language->name }}</li>
            @endforeach
        </ul>
    @endif

    @if ($individual->connection_lived_experience === 'yes-all' ||
        $individual->connection_lived_experience === 'yes-some')
        <h3>{{ __('Does :name have lived experience of the people they can connect to?', ['name' => $individual->firstName]) }}
        </h3>
        {{-- TODO: add attribute getter for this --}}
        <p>{{ App\Enums\CommunityConnectorHasLivedExperience::from($individual->connection_lived_experience)->labels()[$individual->connection_lived_experience] }}
        </p>
    @endif
@endif
