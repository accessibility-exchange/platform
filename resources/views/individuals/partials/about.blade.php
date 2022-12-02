@markdown{{ $individual->getWrittenTranslation('bio', $language) }}@endmarkdown

<h3>
    {{ __('Languages :name uses', ['name' => $individual->first_name]) }}</h3>

<ul>
    @if ($individual->working_languages)
        @foreach ($individual->working_languages as $language)
            <li>{{ get_language_exonym($language) }}</li>
        @endforeach
    @else
        <li>{{ get_language_exonym($individual->user->locale) }}</li>
    @endif
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
                    href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]) }}">@svg('heroicon-o-pencil', 'mr-1')
                    {!! __('Edit :section', [
                        'section' => '<span class="visually-hidden">' . __('groups you can connect to') . '</span>',
                    ]) !!}</a>
            </p>
        @endcan
    </h3>

    <h4>{{ __('Groups in the disability and Deaf community') }}</h4>

    <ul class="tags" role="list">
        @foreach ($individual->disabilityAndDeafConnections as $disabilityType)
            <li class="tag">{{ $disabilityType->name }}</li>
        @endforeach
        @if ($individual->other_disability_type_connection)
            <li class="tag">{{ $individual->other_disability_type_connection }}</li>
        @endif
        @foreach ($individual->livedExperienceConnections as $livedExperience)
            <li class="tag">{{ $livedExperience->name }}</li>
        @endforeach
    </ul>

    @if ($individual->indigenousConnections->count() || $individual->ethnoracialIdentityConnections->count())
        <h4>{{ __('Ethno-racial groups') }}</h4>

        <ul class="tags" role="list">
            @foreach ($individual->indigenousConnections as $indigenousIdentity)
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

    @if ($individual->genderAndSexualityConnections->count() || $individual->statusConnections->count())
        <h3>{{ __('Other identity groups') }}</h3>

        <ul class="tags" role="list">
            @foreach ($individual->statusConnections as $statusIdentity)
                <li class="tag">{{ $statusIdentity->name }}</li>
            @endforeach
            @foreach ($individual->genderAndSexualityConnections as $genderIdentity)
                <li class="tag">{{ $genderIdentity->name }}</li>
            @endforeach
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
