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
                    @cannot('publish', $project) @ariaDisabled aria-describedby="cannot-publish-explanation" @endcannot>{{ __('Publish page') }}</button>
                @cannot('publish', $project)
                    <p id="cannot-publish-explanation">
                        {{ __('You must attend an orientation session and fill in all the required information before you can publish your project.') }}
                    </p>
                @endcannot
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
