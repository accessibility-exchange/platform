<div class="steps flow">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress flow">
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember]) }}">{{ __('About you') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{{ __('Interests') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{{ __('Experiences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]) }}">{{ __('Communication preferences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 5]) }}">{{ __('Access and accomodations') }}</a>
        </li>
    </ol>

    @can('update', $communityMember)
    @if($communityMember->checkStatus('draft'))
        <p>
            <x-hearth-input type="submit" name="preview" :value="__('Preview page')" />
            <x-hearth-input type="submit" name="publish" :value="__('Publish page')" />
        </p>
        <p class="field__hint">{{ __('Once you publish your page, Federally Regulated Organizations can find you and ask you to consult with them.') }}</p>
    @else
        <p>
            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish page')" />
        </p>
    @endif
    @endcan
</div>
