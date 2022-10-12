<div class="steps stack">
    <h3>{{ __('Steps for creating your project') }}</h3>

    <ol class="progress stack">
        <li>
            <a href="{{ localized_route('projects.edit', ['project' => $project]) }}">{{ __('Project details') }}</a>
        </li>
        <li>
            <a
                href="{{ localized_route('projects.edit', ['project' => $project, 'step' => 2]) }}">{{ __('Project team') }}</a>
        </li>
    </ol>

    @can('update', $project)
        @if ($project->checkStatus('draft'))
            <div class="stack">
                @can('publish', $project)
                    <button class="secondary" name="preview" value="1">{{ __('Preview page') }}</button>
                @endcan
                <button name="publish" value="1"
                    @cannot('publish', $project) disabled @endcannot>{{ __('Publish page') }}</button>
                <p>{{ __('You must attend an orientation session before you can publish your projects.') }}</p>
            </div>
        @else
            @can('unpublish', $project)
                <p class="stack">
                    <button name="unpublish" value="1">{{ __('Unpublish page') }}</button>
                </p>
            @endcan
        @endif
    @endcan
</div>
