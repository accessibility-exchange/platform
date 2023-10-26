<x-hearth-alert :title="__('Have questions?')" :dismissable="false" x-show="true">
    <x-interpretation name="{{ __('Have questions?', [], 'en') }}" namespace="have_questions-projects" />
    <p>
        <strong>{{ __('Do you have questions about this project?') }}</strong><br />
        {{ __('Contact :person from :projectable by:', [
            'person' => $project->contact_person_name,
            'projectable' => $project->projectable->name,
        ]) }}
    </p>
    @if ($project->contact_person_email)
        <x-contact-point type="email" :value="$project->contact_person_email" :preferred="$project->preferred_contact_method === 'email' && $project->contact_person_phone" />
    @endif
    @if ($project->contact_person_phone)
        <x-contact-point type="phone" :value="$project->contact_person_phone" :preferred="$project->preferred_contact_method === 'phone' && $project->contact_person_email" :vrs="$project->contact_person_vrs" />
    @endif
</x-hearth-alert>
