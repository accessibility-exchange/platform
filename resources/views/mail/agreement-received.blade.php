@component('mail::message')
{{ __('Your agreement has been received for **:project**. You can now publish your project page and engagement details.', ['project' => $project->name]) }}

@component('mail::button', ['url' => localized_route('projects.manage', $project)])
{{ __('Manage project') }}
@endcomponent
@endcomponent
