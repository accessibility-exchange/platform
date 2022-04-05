<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        <li>
            <a href="{{ localized_route('projects.edit', ['project' => $project]) }}">{{ __('About the project') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('projects.edit', ['project' => $project, 'step' => 2]) }}">{{ __('Team') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('projects.edit', ['project' => $project, 'step' => 3]) }}">{{ __('Who we’re looking for') }}</a>
        </li>
    </ol>

    @can('update', $project)
    @if($project->checkStatus('draft'))
        <p>
            <x-hearth-input type="submit" name="preview" :value="__('Preview project')" />
            <x-hearth-input type="submit" name="publish" :value="__('Publish project')" />
        </p>
        <p class="field__hint">{{ __('Once you publish your project, others can find it.') }}</p>
    @else
        <p>
            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish project')" />
        </p>
    @endif
    @endcan
</div>
