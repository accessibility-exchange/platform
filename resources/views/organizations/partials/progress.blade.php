<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        <li>
            <a
                href="{{ localized_route('organizations.edit', ['organization' => $organization]) }}">{{ __('About your organization') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]) }}">
                {{ __('Groups your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}
            </a>
        </li>
        <li>
            <a
                href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]) }}">{{ __('Interests') }}</a>
        </li>
        <li>
            <a
                href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]) }}">{{ __('Contact Information') }}</a>
        </li>
    </ol>

    @can('update', $organization)
        @if ($organization->checkStatus('draft'))
            <p class="stack">
                @can('publish', $organization)
                    <button class="secondary" name="preview" value="1">{{ __('Preview page') }}</button>
                @endcan
                <button class="secondary" name="publish" value="1"
                    @cannot('publish', $organization) @ariaDisabled aria-describedby="cannot-publish-explanation" @endcannot>{{ __('Publish page') }}</button>
                @cannot('publish', $organization)
                <p id="cannot-publish-explanation">
                    {{ __('You must attend an orientation session and fill in all the required information before you can publish your page.') }}
                </p>
            @endcannot
            </p>
        @else
            @can('unpublish', $organization)
                <p class="stack">
                    <button name="unpublish" value="1">{{ __('Unpublish page') }}</button>
                </p>
            @endcan
        @endif
    @endcan
</div>
