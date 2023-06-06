@component('mail::message')
{{ safe_markdown(':projectable has approved an estimate for their project **:project**.', ['projectable' => $projectable->name, 'project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.show', $project)])
{{ __('Review project details') }}
@endcomponent

{{ safe_markdown('They have been instructed to send their signed agreement to <:email>.', ['email' => settings('email')]) }}
@endcomponent
