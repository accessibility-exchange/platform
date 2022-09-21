@component('mail::message')
{{ __('Your estimate for **:project**, along with a project agreement for to sign, has been sent to [:contact](mailto::contact).', ['project' => $project->name, 'contact' => $project->contact_person_email]) }}

@component('mail::button', ['url' => localized_route('projects.manage', $project)])
{{ __('Manage project') }}
@endcomponent
@endcomponent
