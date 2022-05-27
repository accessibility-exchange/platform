<h3>{{  __('Current projects') }}</h3>
@forelse ($regulatedOrganization->currentProjects as $project)
    <x-project-card :project="$project" :level="4" :showRegulatedOrganization="false" />
@empty
    <p>{{ __('No projects found.') }}</p>
@endforelse
<h3>{{  __('Completed projects') }}</h3>
@forelse ($regulatedOrganization->pastProjects as $project)
    <x-project-card :project="$project" :level="4" :showRegulatedOrganization="false" />
@empty
    <p>{{ __('No projects found.') }}</p>
@endforelse
