@component('mail::message')
{{
    safe_markdown(
        'Please contact :name to facilitate their access needs being met on the engagement [:engagement](:engagement-url).',
        [
            'name' => $individual->name,
            'engagement' => $engagement->name,
            'engagement-url' => localized_route('engagements.show', $engagement)
        ]
    )
}}

@component('mail::panel')
<h2>
@if ($individual->preferred_contact_person === App\Enums\ContactPerson::Me->value)
    {{ __('Contact :name', ['name' => $individual->name]) }}
@else
    {{ __('Contact :name’s support person, :support_person_name', ['name' => $individual->name, 'support_person_name' => $individual->user->support_person_name]) }}
@endif

</h2>

@if ($individual->contact_email)
<div>
    <strong>{{ __('Email') }}{{ $individual->preferred_contact_method === 'email' && $individual->contact_phone ? ' (' . __('preferred') . ')' : '' }}:</strong>
    <a href="mailto:{{ $individual->contact_email }}">{{ $individual->contact_email }}</a>
</div>
@endif
@if ($individual->contact_phone)
<div>
    <span>
        <strong>{{ __('Phone') }}{{ $individual->preferred_contact_method === 'phone' && $individual->contact_email ? ' (' . __('preferred') . ')' : '' }}:</strong>
        {{ $individual->contact_phone }}
        @if ($individual->contact_vrs)
            ({{ __('requires VRS') }})
        @endif
    </span>
</div>
@endif
@endcomponent
@endcomponent
