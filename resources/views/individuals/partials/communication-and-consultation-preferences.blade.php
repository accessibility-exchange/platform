@if ($individual->user->preferred_contact_person === App\Enums\ContactPerson::Me->value)
    <h3>{{ __('Contact :name', ['name' => $individual->name]) }}</h3>
@else
    <h3>{{ __('Contact :name’s support person, :support_person_name', ['name' => $individual->name, 'support_person_name' => $individual->user->support_person_name]) }}
    </h3>
@endif
@if ($individual->contact_email)
    <x-contact-point :type="App\Enums\ContactMethod::Email->value" :value="$individual->contact_email" :preferred="$individual->preferred_contact_method === App\Enums\ContactMethod::Email->value &&
        $individual->contact_phone" />
@endif
@if ($individual->contact_phone)
    <x-contact-point :type="App\Enums\ContactMethod::Phone->value" :value="$individual->contact_phone" :preferred="$individual->preferred_contact_method === App\Enums\ContactMethod::Phone->value &&
        $individual->contact_email" :vrs="$individual->contact_vrs" />
@endif

<h3>{{ __('Types of meetings offered') }}</h3>

<ul>
    @foreach ($individual->meeting_types ?? [] as $meeting_type)
        <li>{{ App\Enums\MeetingType::from($meeting_type)->labels()[$meeting_type] }}</li>
    @endforeach
</ul>
