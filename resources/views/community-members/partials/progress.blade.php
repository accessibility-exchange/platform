<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember]) }}">{{ __('About you') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{{ __('Experiences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{{ __('Interests') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]) }}">{{ __('Communication preferences') }}</a>
        </li>
    </ol>

    @can('update', $communityMember)
    @if($communityMember->checkStatus('draft'))
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
