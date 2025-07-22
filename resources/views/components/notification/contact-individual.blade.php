@props(['individual'])
<div class="box stack">
    <h4 class="h5">
        @if ($individual->preferred_contact_person === App\Enums\ContactPerson::Me->value)
            {{ __('Contact :name', ['name' => $individual->name]) }}
        @else
            {{ __('Contact :name’s support person, :support_person_name', ['name' => $individual->name, 'support_person_name' => $individual->user->support_person_name]) }}
        @endif
    </h4>

    @if ($individual->contact_email)
        <x-contact-point :type="App\Enums\ContactMethod::Email->value" :value="$individual->contact_email" :preferred="$individual->preferred_contact_method === App\Enums\ContactMethod::Email->value &&
            $individual->contact_phone" />
    @endif
    @if ($individual->contact_phone)
        <x-contact-point :type="App\Enums\ContactMethod::Phone->value" :value="$individual->contact_phone" :preferred="$individual->preferred_contact_method === App\Enums\ContactMethod::Phone->value &&
            $individual->contact_email" :vrs="$individual->contact_vrs" />
    @endif
</div>
