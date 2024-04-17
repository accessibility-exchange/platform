<h3>{{ $regulatedOrganization->contact_person_name }}</h3>
<x-interpretation name="{{ __('contact person', [], 'en') }}" />

@if ($regulatedOrganization->contact_person_email)
    <x-contact-point type="email" :value="$regulatedOrganization->contact_person_email" :preferred="$regulatedOrganization->preferred_contact_method === 'email' &&
        $regulatedOrganization->contact_person_phone" />
@endif
@if ($regulatedOrganization->contact_person_phone)
    <x-contact-point type="phone" :value="$regulatedOrganization->contact_person_phone" :preferred="$regulatedOrganization->preferred_contact_method === 'phone' &&
        $regulatedOrganization->contact_person_email" :vrs="$regulatedOrganization->contact_person_vrs" />
@endif
