<x-card :level="4">
    <x-slot name="title">{{ __('Have questions?') }}</x-slot>
    {!! Str::markdown(
        __('Please email :name at <:email>:extra.', [
            'name' => $project->contact_person_name,
            'email' => $project->contact_person_email,
            'extra' => $project->contact_person_phone
                ? __(' or phone :phone', ['phone' => $project->contact_person_phone->formatForCountry('CA')])
                : '',
        ]),
    ) !!}
</x-card>
