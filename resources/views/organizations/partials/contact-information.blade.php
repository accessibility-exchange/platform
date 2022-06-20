<h3>{{ $organization->contact_person_name }}</h3>

<p>
    <strong>{{ __('Email') }}</strong><br />
    @if($organization->preferred_contact_method === 'email')<em>{{ __('Preferred contact method') }}</em><br />@endif
    <a href="mailto:{{ $organization->contact_person_email }}">{{ $organization->contact_person_email }}</a>
</p>

<p>
    <strong>{{ __('Phone number') }}</strong><br />
    @if($organization->preferred_contact_method === 'phone')<em>{{ __('Preferred contact method') }}</em><br />@endif
    <a href="tel:{{ $organization->contact_person_phone }}">{{ $organization->contact_person_phone }}</a>
    @if($organization->contact_person_vrs)<br />
    {{ __(':name requires VRS (Video Relay Service) for phone calls', ['name' => $organization->contact_person_name]) }}
    @endif
</p>
