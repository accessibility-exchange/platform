@component('mail::message')
{{ safe_markdown('Your agreement has been received for **:project**. You can now publish your project page and engagement details.', ['project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.manage-estimates-and-agreements', $project)])
{{ __('Manage project') }}
@endcomponent
@endcomponent
