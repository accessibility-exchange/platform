<h3>{{ __('Groups in the disability and Deaf community') }}</h3>

<ul role="list" class="tags">
@foreach($organization->livedExperiences as $livedExperience)
    <li class="tag">{{ $livedExperience->name }}</li>
@endforeach
@foreach($organization->disabilityTypes as $disabilityType)
    <li class="tag">{{ $disabilityType->name }}</li>
@endforeach
@if($organization->other_disability_type)
    <li class="tag">{{ $organization->other_disability_type }}</li>
@endif
</ul>

@if($organization->extra_attributes->has_indigenous_identities || $organization->extra_attributes->has_ethnoracial_identities)
<h3>{{ __('Ethno-racial groups') }}</h3>

<ul role="list" class="tags">
    @foreach($organization->indigenousIdentities as $indigenousIdentity)
    <li class="tag">{{ $indigenousIdentity->name }}</li>
    @endforeach
    @foreach($organization->ethnoracialIdentities as $ethnoracialIdentity)
    <li class="tag">{{ $ethnoracialIdentity->name }}</li>
    @endforeach
    @if($organization->other_ethnoracial)
    <li class="tag">{{ $organization->other_ethnoracial_identity }}</li>
    @endif
</ul>
@endif

@if($organization->extra_attributes->has_gender_and_sexual_identities || $organization->extra_attributes->has_refugee_and_immigrant_constituency)
<h3>{{ __('Other identity groups') }}</h3>

<ul role="list" class="tags">
    @if($organization->extra_attributes->has_refugee_and_immigrant_constituency)
    <li class="tag">{{ __('Refugees and/or immigrants') }}</li>
    @endif
    @foreach($organization->genderIdentities as $genderIdentity)
    <li class="tag">{{ $genderIdentity->name_plural }}</li>
    @endforeach
    @if($organization->constituencies->contains($transPeople))
    <li class="tag">{{ $transPeople->name_plural }}</li>
    @endif
    @if($organization->constituencies->contains($twoslgbtqiaplusPeople))
    <li class="tag">{{ $twoslgbtqiaplusPeople->name_plural }}</li>
    @endif
</ul>
@endif

@if($organization->extra_attributes->has_age_brackets)
<h3>{{ __('Age groups') }}</h3>

<ul role="list" class="tags">
    @foreach($organization->ageBrackets as $ageBracket)
        <li class="tag">{{ $ageBracket->name }}</li>
    @endforeach
</ul>
@endif

@if($organization->constituentLanguages->count() > 0)
<h3>{{ __('Language groups') }}</h3>

<ul role="list" class="tags">
    @foreach($organization->constituentLanguages as $language)
        <li class="tag">{{ $language->name }}</li>
    @endforeach
</ul>
@endif

@if($organization->staff_lived_experience === 'yes')
<h3>{{ __('Staff lived experience') }}</h3>

<p>{{ __('This organization has people on staff who have lived experience of the communities they :represent_or_serve_and_support.', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</p>
@endif
