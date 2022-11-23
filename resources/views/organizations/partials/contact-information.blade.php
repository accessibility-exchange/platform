<h3>{{ $organization->contact_person_name }}</h3>

@if ($organization->contact_person_email)
    <x-contact-point type="email" :value="$organization->contact_person_email" :preferred="$organization->preferred_contact_method === 'email' && $organization->contact_person_phone" />
@endif
@if ($organization->contact_person_phone)
    <x-contact-point type="phone" :value="$organization->contact_person_phone" :preferred="$organization->preferred_contact_method === 'phone' && $organization->contact_person_email" :vrs="$organization->contact_person_vrs" />
@endif
