@component('mail::message')
{{
    safe_markdown(
        'Your estimate for **:project**, along with a project agreement for you to sign, has been sent to <:contact>.',
        ['project' => $project->name, 'contact' => $project->contact_person_email]
    )
}}

@component('mail::button', ['url' => localized_route('projects.manage-estimates-and-agreements', $project)])
{{ __('Manage project') }}
@endcomponent
@endcomponent
