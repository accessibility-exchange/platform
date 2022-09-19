@component('mail::message')
{{ __(':projectable has requested an estimate for their project “:project”.', ['projectable' => $project->projectable->name, 'project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.show', $project)])
{{ __('Review Project Details') }}
@endcomponent

{{ __('Once you’ve reviewed the project details, send the estimate and agreement to :contact and update the estimate status by visiting the link below and searching for :projectable.', ['contact' => $project->contact_person_email, 'projectable' => $project->projectable->name]) }}

@component('mail::button', ['url' => $url])
{{ __('Update Estimate Status') }}
@endcomponent
@endcomponent
