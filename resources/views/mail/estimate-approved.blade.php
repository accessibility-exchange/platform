@component('mail::message')
{{ __(':projectable has approved an estimate for their project :project.', ['projectable' => $projectable->name, 'project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.show', $project)])
{{ __('Review project details') }}
@endcomponent

{{ __('They have been instructed to send their signed agreement to [support@accessibilityexchange.ca](mailto:support@accessibilityexchange.ca).') }}
@endcomponent
