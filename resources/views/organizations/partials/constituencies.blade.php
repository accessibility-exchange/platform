<h3>{{ __('People with disabilities, Deaf persons, and/or supporters') }}</h3>

<ul role="list" class="tags">
@foreach($organization->livedExperiences as $livedExperience)
    <li class="tag">{{ $livedExperience->name }}</li>
@endforeach
</ul>

@if(in_array(1, $organization->livedExperiences->pluck('id')->toArray()))
<h3>{{ __('Disabilities') }}</h3>

<ul role="list" class="tags">
    @foreach($organization->disabilityTypes as $disabilityType)
        <li class="tag">{{ $disabilityType->name }}</li>
    @endforeach
    @if($organization->other_disability_type)
        <li class="tag">{{ $organization->other_disability_type }}</li>
    @endif
</ul>
@endif

@if($organization->extra_attributes->has_indigenous_identities)
    <h3>{{ __('Indigenous identities') }}</h3>

    <ul role="list" class="tags">
        @foreach($organization->indigenousIdentities as $indigenousIdentity)
            <li class="tag">{{ $indigenousIdentity->name }}</li>
        @endforeach
    </ul>
@endif

@if($organization->extra_attributes->has_gender_and_sexual_identities)
<h3>{{ __('Gender and sexual identities') }}</h3>

<ul role="list" class="tags">
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

@if($organization->extra_attributes->has_refugee_and_immigrant_constituency)
<h3>{{ __('Refugees and/or immigrants') }}</h3>
<ul role="list" class="tags"><li class="tag">{{ __('Yes') }}</li></ul>
@endif

@if($organization->extra_attributes->has_age_brackets)
<h3>{{ __('Age groups') }}</h3>

<ul role="list" class="tags">
    @foreach($organization->ageBrackets as $ageBracket)
        <li class="tag">{{ $ageBracket->name }}</li>
    @endforeach
</ul>
@endif

@if($organization->extra_attributes->has_ethnoracial_identities)
    <h3>{{ __('Ethno-racial identities') }}</h3>

    <ul role="list" class="tags">
        @foreach($organization->ethnoracialIdentities as $ethnoracialIdentity)
            <li class="tag">{{ $ethnoracialIdentity->name }}</li>
        @endforeach
    </ul>
@endif

@if($organization->constituentLanguages->count() > 0)
<h3>{{ __('Languages') }}</h3>

<ul role="list" class="tags">
    @foreach($organization->constituentLanguages as $language)
        <li class="tag">{{ $language->name }}</li>
    @endforeach
</ul>
@endif

<hr class="divider" />

<h3>{{ __('Staff lived experience') }}</h3>
