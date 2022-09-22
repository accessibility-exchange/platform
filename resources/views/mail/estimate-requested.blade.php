@component('mail::message')
{{ __(':projectable has requested an estimate for their project **:project**.', ['projectable' => $projectable->name, 'project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.show', $project)])
{{ __('Review project details') }}
@endcomponent

{{ __('Once you’ve reviewed the project details, please:') }}

{{ __('1. Send the estimate and agreement to [:contact](mailto::contact).', ['contact' => $project->contact_person_email]) }}
{{ __('2. Mark the estimate as "returned" by visiting the link below and searching for :projectable.', ['projectable' => $projectable->name]) }}

@component('mail::button', ['url' => localized_route('admin.estimates-and-agreements')])
{{ __('Update estimate status') }}
@endcomponent
@endcomponent
