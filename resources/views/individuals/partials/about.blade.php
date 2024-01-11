{{ safe_nl2br($individual->getTranslation('bio', $language)) }}

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
            <li>{{ App\Enums\ConsultingService::labels()[$service] }}</li>
        @endforeach
    </ul>
@endif

@if ($individual->isConnector())
    <x-section-heading level="3" :name="__('As a Community Connector, :name can connect to:', ['name' => $individual->firstName])" :model="$individual" :href="localized_route('individuals.edit', ['individual' => $individual, 'step' => 2])" :linkText="__('groups you can connect to')" />

    <h4>{{ __('Groups in the disability and Deaf community') }}</h4>

    <ul class="tags" role="list">
        @if ($individual->extra_attributes->get('cross_disability_and_deaf_connections'))
            <li class="tag">{{ __('Any group') }}</li>
        @endif
        @foreach ($individual->disabilityAndDeafConnections as $disabilityType)
            <li class="tag">{{ $disabilityType->name }}</li>
        @endforeach
        @if ($individual->other_disability_type_connection)
            <li class="tag">{{ $individual->other_disability_type_connection }}</li>
        @endif
        @if ($individual->other_disability_connection)
            <li class="tag">{{ $individual->other_disability_connection }}</li>
        @endif
        @foreach ($individual->livedExperienceConnections as $livedExperience)
            <li class="tag">{{ $livedExperience->name }}</li>
        @endforeach
    </ul>

    @if (
        $individual->hasConnections('indigenousConnections') ||
            $individual->hasConnections('ethnoracialIdentityConnections'))
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

    @if ($individual->hasConnections('genderAndSexualityConnections') || $individual->hasConnections('statusConnections'))
        <h4>{{ __('Other identity groups') }}</h4>

        <ul class="tags" role="list">
            @foreach ($individual->statusConnections as $statusIdentity)
                <li class="tag">{{ $statusIdentity->name }}</li>
            @endforeach
            @foreach ($individual->genderAndSexualityConnections as $genderIdentity)
                <li class="tag">{{ $genderIdentity->name }}</li>
            @endforeach
        </ul>
    @endif

    @if ($individual->hasConnections('ageBracketConnections'))
        <h4>{{ __('Age groups') }}</h4>

        <ul class="tags" role="list">
            @foreach ($individual->ageBracketConnections as $ageBracket)
                <li class="tag">{{ $ageBracket->name }}</li>
            @endforeach
        </ul>
    @endif

    @if ($individual->hasConnections('languageConnections'))
        <h4>{{ __('Language groups') }}</h4>

        <ul class="tags" role="list">
            @foreach ($individual->languageConnections as $language)
                <li class="tag">{{ $language->name }}</li>
            @endforeach
        </ul>
    @endif

    @if ($individual->connection_lived_experience === 'yes-all' || $individual->connection_lived_experience === 'yes-some')
        <h3>{{ __('Does :name have lived experience of the people they can connect to?', ['name' => $individual->firstName]) }}
        </h3>
        {{-- TODO: add attribute getter for this --}}
        <p>{{ App\Enums\CommunityConnectorHasLivedExperience::from($individual->connection_lived_experience)->labels()[$individual->connection_lived_experience] }}
        </p>
    @endif
@endif
