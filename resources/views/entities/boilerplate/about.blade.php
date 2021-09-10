<p>Here's a little bit of information about our business.</p>

<x-header :level="$level">What we offer</x-header>

<ul>
    <li>Here's a list of services we offer</li>
</ul>

<x-header :level="$level">Current projects</x-header>

@forelse($entity->currentProjects as $project)
<x-project-card :project="$project" :showEntity="false" />
@empty
<p>{{ __('project.none_found') }}</p>
@endforelse
