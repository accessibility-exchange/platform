@component('mail::message')
{{ safe_markdown(':projectable has requested an estimate for their project **:project**.', ['projectable' => $projectable->name, 'project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.show', $project)])
{{ __('Review project details') }}
@endcomponent

<p>{{ __('Once you’ve reviewed the project details, please:') }}</p>

<ol>
    <li>{{ safe_inlineMarkdown('Send the estimate and agreement to <:contact>.', ['contact' => $project->contact_person_email]) }}</li>
    <li>{{ __('Mark the estimate as "returned" by visiting the link below and searching for :projectable.', ['projectable' => $projectable->name]) }}</li>
</ol>

@component('mail::button', ['url' => localized_route('admin.estimates-and-agreements')])
{{ __('Update estimate status') }}
@endcomponent
@endcomponent
