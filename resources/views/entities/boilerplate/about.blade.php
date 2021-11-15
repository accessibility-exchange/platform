<p>Here's a little bit of information about our business.</p>

<x-header :level="$level">{{ __('What we offer') }}</x-header>

<ul>
    <li>Here's a list of services we offer</li>
</ul>

<x-header :level="$level">{{ __('Current projects') }}</x-header>

@forelse($entity->currentProjects as $project)
<x-project-card :project="$project" :level="4" :showEntity="false" />
@empty
<p>{{ __('No projects found.') }}</p>
@endforelse
