<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        <li>
            <a href="{{ localized_route('organizations.edit', ['organization' => $organization]) }}">{{ __('About your organization') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]) }}">{{ __('Interests') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]) }}">{{ __('Contact Information') }}</a>
        </li>
    </ol>

    @can('update', $organization)
        @if($organization->checkStatus('draft'))
            <p class="stack">
                <x-hearth-input class="secondary" type="submit" name="preview" :value="__('Preview page')" />
                <x-hearth-input class="secondary" type="submit" name="publish" :value="__('Publish page')" />
            </p>
        @else
            <p class="stack">
                <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish page')" />
            </p>
        @endif
    @endcan
</div>
